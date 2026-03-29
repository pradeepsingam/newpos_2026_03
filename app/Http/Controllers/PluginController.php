<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Plugin;
use App\Models\PluginVersion;
use App\Models\TenantPlugin;
use App\Support\Plugins\PluginManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PluginController extends Controller
{
    public function __construct(
        protected PluginManager $pluginManager
    ) {
    }

    public function index(): View
    {
        $this->authorize('viewAny', Plugin::class);

        $businesses = Business::query()->orderBy('name')->get();

        $plugins = Plugin::query()
            ->with(['versions' => fn ($query) => $query->latest('id')])
            ->latest()
            ->get()
            ->map(function (Plugin $plugin) use ($businesses) {
                $assignments = TenantPlugin::query()
                    ->withoutGlobalScopes()
                    ->with(['version', 'business'])
                    ->where('plugin_id', $plugin->id)
                    ->get()
                    ->keyBy('business_id');

                return [
                    'plugin' => $plugin,
                    'versions' => $plugin->versions,
                    'businesses' => $businesses->map(function (Business $business) use ($assignments) {
                        return [
                            'business' => $business,
                            'assignment' => $assignments->get($business->id),
                        ];
                    }),
                ];
            });

        return view('plugins.index', [
            'plugins' => $plugins,
            'businesses' => $businesses,
        ]);
    }

    public function upload(Request $request): RedirectResponse
    {
        $this->authorize('upload', Plugin::class);

        $request->validate([
            'plugin_zip' => ['required', 'file'],
        ]);

        $version = $this->pluginManager->installGlobalPackage($request->file('plugin_zip'), $request->user());

        return redirect()
            ->route('plugins.index')
            ->with('status', "Plugin {$version->plugin->name} v{$version->version} uploaded and approved globally.");
    }

    public function assign(Request $request, Plugin $plugin): RedirectResponse
    {
        $this->authorize('assign', $plugin);

        $data = $request->validate([
            'business_id' => ['required', 'integer', 'exists:businesses,id'],
            'plugin_version_id' => ['required', 'integer', 'exists:plugin_versions,id'],
        ]);

        $business = Business::query()->findOrFail($data['business_id']);
        $pluginVersion = PluginVersion::query()
            ->where('plugin_id', $plugin->id)
            ->where('is_approved', true)
            ->findOrFail($data['plugin_version_id']);

        $this->pluginManager->assignVersionToBusiness($pluginVersion, $business, $request->user());

        return redirect()
            ->route('plugins.index')
            ->with('status', "{$plugin->name} {$pluginVersion->version} enabled for {$business->name}.");
    }

    public function deactivate(Request $request, Plugin $plugin): RedirectResponse
    {
        $this->authorize('deactivate', $plugin);

        $data = $request->validate([
            'business_id' => ['required', 'integer', 'exists:businesses,id'],
        ]);

        $business = Business::query()->findOrFail($data['business_id']);
        $this->pluginManager->disableForBusiness($plugin, $business);

        return redirect()
            ->route('plugins.index')
            ->with('status', "{$plugin->name} disabled for {$business->name}.");
    }
}

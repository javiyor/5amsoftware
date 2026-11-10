<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Support\AuditService;
use App\Services\Support\CompanySettingsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyProfileController extends Controller
{
    public function __construct(
        private readonly CompanySettingsService $settings,
        private readonly AuditService $audit,
    ) {}

    public function edit(): View
    {
        return view('admin.company-profile.form', [
            'settings' => $this->settings->all(),
            'visitCounts' => DB::table('site_visit_counters')->pluck('visits', 'key'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:180'],
            'company_cuit' => ['nullable', 'string', 'max:30'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'company_profession' => ['nullable', 'string', 'max:180'],
            'company_profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'company_promo_video' => ['nullable', 'file', 'mimes:mp4,webm,mov', 'max:40960'],
            'company_promo_video_url' => ['nullable', 'url', 'max:500'],
            'remove_promo_video' => ['sometimes', 'boolean'],
        ]);

        $oldPhoto = $this->settings->get('company_profile_photo');
        $oldVideo = $this->settings->get('company_promo_video');

        foreach (['company_name', 'company_cuit', 'company_address', 'company_profession', 'company_promo_video_url'] as $key) {
            $this->settings->set($key, $validated[$key] ?? '', 'profile');
        }

        if ($request->hasFile('company_profile_photo')) {
            $photo = $request->file('company_profile_photo')->store('company/profile', 'public');
            $this->settings->set('company_profile_photo', $photo, 'profile');

            if ($oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }
        }

        if ($request->boolean('remove_promo_video') && ! $request->hasFile('company_promo_video')) {
            $this->settings->set('company_promo_video', '', 'profile');
            $this->settings->set('company_promo_video_url', '', 'profile');

            if ($oldVideo) {
                Storage::disk('public')->delete($oldVideo);
            }
        }

        if ($request->hasFile('company_promo_video')) {
            $video = $request->file('company_promo_video')->store('company/promo', 'public');
            $this->settings->set('company_promo_video', $video, 'profile');
            $this->settings->set('company_promo_video_url', '', 'profile');

            if ($oldVideo) {
                Storage::disk('public')->delete($oldVideo);
            }
        } elseif (filled($validated['company_promo_video_url'] ?? null) && $oldVideo) {
            $this->settings->set('company_promo_video', '', 'profile');
            Storage::disk('public')->delete($oldVideo);
        }

        $this->audit->log('updated', 'company_profile', null, null, [
            'company_name' => $validated['company_name'],
            'company_cuit' => $validated['company_cuit'] ?? '',
            'company_address' => $validated['company_address'] ?? '',
            'company_profession' => $validated['company_profession'] ?? '',
            'company_promo_video_url' => $validated['company_promo_video_url'] ?? '',
        ]);

        return redirect()
            ->route('admin.company-profile.edit')
            ->with('status', 'Los datos del perfil se guardaron correctamente.');
    }
}

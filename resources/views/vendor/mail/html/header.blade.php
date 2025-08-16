@inject('settings', App\Models\Setting::class)

@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            {{-- @if (trim($slot) === 'Laravel') --}}
                <img src={{ asset('storage' . $settings::getValue('site.logo')) }} class="logo" alt="SaaS PHP">
            {{-- @else
                {{ $slot }}
            @endif --}}
        </a>
    </td>
</tr>

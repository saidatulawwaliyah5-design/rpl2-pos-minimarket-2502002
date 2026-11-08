<?php
/**
 * Pustaka ilustrasi SVG custom untuk katalog publik.
 * Semua ikon digambar manual (bukan emoji/font icon/foto) supaya
 * tampilan konsisten di semua browser & device, dan bebas isu hak cipta.
 */

function illustrasiKategori(?string $namaJenis, int $size = 64): string {
    $n = strtolower((string)$namaJenis);

    if (str_contains($n, 'sembako')) {
        return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="32" cy="56" rx="18" ry="4" fill="#000" opacity="0.06"/>
            <path d="M20 24C20 24 18 20 24 16C24 16 26 12 32 12C38 12 40 16 40 16C46 20 44 24 44 24L46 48C46 52 42 54 32 54C22 54 18 52 18 48L20 24Z" fill="#F59E0B"/>
            <path d="M20 24C20 24 18 20 24 16C24 16 26 12 32 12C38 12 40 16 40 16C46 20 44 24 44 24" stroke="#B45309" stroke-width="2" stroke-linecap="round"/>
            <rect x="27" y="10" width="10" height="6" rx="2" fill="#92400E"/>
            <path d="M25 30L25 46M32 28L32 48M39 30L39 46" stroke="#FDE68A" stroke-width="2" stroke-linecap="round"/>
        </svg>';
    }

    if (str_contains($n, 'minuman')) {
        return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="32" cy="56" rx="16" ry="4" fill="#000" opacity="0.06"/>
            <path d="M22 22H42L39 50C39 52 37 54 34 54H30C27 54 25 52 25 50L22 22Z" fill="#38BDF8"/>
            <path d="M22 22H42L41 28H23L22 22Z" fill="#0EA5E9"/>
            <rect x="30" y="8" width="4" height="18" rx="2" fill="#E5E7EB" transform="rotate(8 32 17)"/>
            <path d="M27 34C27 34 29 37 32 37C35 37 37 34 37 34" stroke="#BAE6FD" stroke-width="2" stroke-linecap="round"/>
            <circle cx="30" cy="42" r="1.5" fill="#E0F2FE"/>
            <circle cx="35" cy="46" r="1.5" fill="#E0F2FE"/>
        </svg>';
    }

    if (str_contains($n, 'snack') || str_contains($n, 'makanan ringan')) {
        return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="32" cy="56" rx="17" ry="4" fill="#000" opacity="0.06"/>
            <path d="M19 24C19 20 22 18 32 18C42 18 45 20 45 24L47 46C47 51 42 54 32 54C22 54 17 51 17 46L19 24Z" fill="#FB7185"/>
            <path d="M19 24L24 20L28 24L32 20L36 24L40 20L45 24" stroke="#FDA4AF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="27" cy="36" r="2.5" fill="#FECDD3"/>
            <circle cx="37" cy="34" r="2" fill="#FECDD3"/>
            <circle cx="32" cy="43" r="2.5" fill="#FECDD3"/>
        </svg>';
    }

    if (str_contains($n, 'alat tulis')) {
        return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="32" cy="56" rx="17" ry="4" fill="#000" opacity="0.06"/>
            <rect x="14" y="40" width="30" height="8" rx="2" fill="#5EEAD4" transform="rotate(-8 14 40)"/>
            <path d="M42 14L50 22L28 44L18 46L20 36L42 14Z" fill="#FBBF24"/>
            <path d="M42 14L50 22L46 26L38 18L42 14Z" fill="#F59E0B"/>
            <path d="M20 36L18 46L28 44L20 36Z" fill="#FDE68A"/>
            <rect x="17" y="43" width="4" height="4" fill="#78350F"/>
        </svg>';
    }

    if (str_contains($n, 'rumah tangga')) {
        return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="32" cy="56" rx="15" ry="4" fill="#000" opacity="0.06"/>
            <rect x="24" y="24" width="16" height="30" rx="4" fill="#2DD4BF"/>
            <rect x="27" y="14" width="6" height="12" rx="2" fill="#0F766E"/>
            <path d="M33 16H41C43 16 44 18 42 19L34 22" stroke="#134E4A" stroke-width="2.5" stroke-linecap="round"/>
            <path d="M44 12L47 9M46 15L50 15M43 18L47 20" stroke="#5EEAD4" stroke-width="2" stroke-linecap="round"/>
            <rect x="27" y="32" width="10" height="14" rx="2" fill="#CCFBF1" opacity="0.6"/>
        </svg>';
    }

    if (str_contains($n, 'perawatan') || str_contains($n, 'kosmetik')) {
        return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="32" cy="56" rx="14" ry="4" fill="#000" opacity="0.06"/>
            <rect x="23" y="22" width="18" height="32" rx="6" fill="#A78BFA"/>
            <rect x="27" y="12" width="10" height="12" rx="3" fill="#7C3AED"/>
            <rect x="29" y="8" width="6" height="6" rx="2" fill="#5B21B6"/>
            <rect x="26" y="34" width="12" height="10" rx="2" fill="#DDD6FE" opacity="0.7"/>
        </svg>';
    }

    // default / lainnya — kotak paket
    return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
        <ellipse cx="32" cy="56" rx="16" ry="4" fill="#000" opacity="0.06"/>
        <path d="M32 14L50 24V44L32 54L14 44V24L32 14Z" fill="#94A3B8"/>
        <path d="M32 14L50 24L32 34L14 24L32 14Z" fill="#CBD5E1"/>
        <path d="M32 34V54" stroke="#64748B" stroke-width="1.5"/>
    </svg>';
}

function ikonKecil(string $nama, int $size = 20, string $warna = 'currentColor'): string {
    $paths = [
        'cart'    => '<path d="M3 4h2l2.4 12h11.2L21 8H6" stroke="' . $warna . '" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="9" cy="20" r="1.4" fill="' . $warna . '"/><circle cx="17" cy="20" r="1.4" fill="' . $warna . '"/>',
        'search'  => '<circle cx="10.5" cy="10.5" r="6.5" stroke="' . $warna . '" stroke-width="1.8"/><path d="M20 20L15.5 15.5" stroke="' . $warna . '" stroke-width="1.8" stroke-linecap="round"/>',
        'home'    => '<path d="M4 11L12 4L20 11" stroke="' . $warna . '" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 10V19C6 19.5 6.4 20 7 20H17C17.6 20 18 19.5 18 19V10" stroke="' . $warna . '" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'grid'    => '<rect x="4" y="4" width="7" height="7" rx="1.5" stroke="' . $warna . '" stroke-width="1.8"/><rect x="13" y="4" width="7" height="7" rx="1.5" stroke="' . $warna . '" stroke-width="1.8"/><rect x="4" y="13" width="7" height="7" rx="1.5" stroke="' . $warna . '" stroke-width="1.8"/><rect x="13" y="13" width="7" height="7" rx="1.5" stroke="' . $warna . '" stroke-width="1.8"/>',
        'tag'     => '<path d="M11 4H6C5 4 4 5 4 6V11C4 11.5 4.2 12 4.6 12.4L13.6 21.4C14.4 22.2 15.6 22.2 16.4 21.4L21.4 16.4C22.2 15.6 22.2 14.4 21.4 13.6L12.4 4.6C12 4.2 11.5 4 11 4Z" stroke="' . $warna . '" stroke-width="1.8" stroke-linejoin="round"/><circle cx="8.5" cy="8.5" r="1.5" fill="' . $warna . '"/>',
        'gift'    => '<rect x="4" y="10" width="16" height="10" rx="1.5" stroke="' . $warna . '" stroke-width="1.8"/><path d="M4 10H20V7.5C20 6.7 19.3 6 18.5 6H5.5C4.7 6 4 6.7 4 7.5V10Z" stroke="' . $warna . '" stroke-width="1.8" stroke-linejoin="round"/><path d="M12 6V20" stroke="' . $warna . '" stroke-width="1.8"/><path d="M12 6C12 6 9 6 8.5 4.5C8.2 3.5 9 3 10 3.5C11.5 4.2 12 6 12 6Z" fill="' . $warna . '"/><path d="M12 6C12 6 15 6 15.5 4.5C15.8 3.5 15 3 14 3.5C12.5 4.2 12 6 12 6Z" fill="' . $warna . '"/>',
        'lock'    => '<rect x="5" y="11" width="14" height="10" rx="2" stroke="' . $warna . '" stroke-width="1.8"/><path d="M8 11V8C8 5.8 9.8 4 12 4C14.2 4 16 5.8 16 8V11" stroke="' . $warna . '" stroke-width="1.8"/><circle cx="12" cy="16" r="1.6" fill="' . $warna . '"/>',
        'scale'   => '<path d="M12 3V21M7 6H17M4 10L7 6L10 10M14 10L17 6L20 10M4 10C4 12 5.5 13 7 13C8.5 13 10 12 10 10M14 10C14 12 15.5 13 17 13C18.5 13 20 12 20 10M7 21H17" stroke="' . $warna . '" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'store'   => '<path d="M4 9L5 4H19L20 9" stroke="' . $warna . '" stroke-width="1.8" stroke-linejoin="round"/><path d="M4 9C4 10.5 5 11.5 6.5 11.5C8 11.5 9 10.5 9 9C9 10.5 10 11.5 11.5 11.5C13 11.5 14 10.5 14 9C14 10.5 15 11.5 16.5 11.5C18 11.5 19 10.5 19 9" stroke="' . $warna . '" stroke-width="1.8" stroke-linecap="round"/><path d="M5.5 11V19C5.5 19.6 6 20 6.5 20H17.5C18 20 18.5 19.6 18.5 19V11" stroke="' . $warna . '" stroke-width="1.8"/><rect x="10" y="14" width="4" height="6" stroke="' . $warna . '" stroke-width="1.8"/>',
        'heart'   => '<path d="M12 20C12 20 3 14.5 3 8.5C3 5.5 5.5 3.5 8 3.5C10 3.5 11.5 4.5 12 6C12.5 4.5 14 3.5 16 3.5C18.5 3.5 21 5.5 21 8.5C21 14.5 12 20 12 20Z" stroke="' . $warna . '" stroke-width="1.8" stroke-linejoin="round"/>',
        'plus'    => '<path d="M12 5V19M5 12H19" stroke="' . $warna . '" stroke-width="2.4" stroke-linecap="round"/>',
        'arrow'   => '<path d="M5 12H19M19 12L13 6M19 12L13 18" stroke="' . $warna . '" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'star'    => '<path d="M12 3L14.6 9L21 9.6L16.2 13.8L17.6 20.2L12 16.8L6.4 20.2L7.8 13.8L3 9.6L9.4 9L12 3Z" fill="' . $warna . '"/>',
    ];
    $inner = $paths[$nama] ?? $paths['grid'];
    return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:inline-block; vertical-align:middle;">' . $inner . '</svg>';
}

function ilustrasiHeroKeranjang(): string {
    return '<svg width="260" height="260" viewBox="0 0 260 260" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="130" cy="130" r="110" fill="url(#heroGlow)" opacity="0.15"/>
        <defs>
          <radialGradient id="heroGlow" cx="50%" cy="50%" r="50%">
            <stop offset="0%" stop-color="#FBBF24"/>
            <stop offset="100%" stop-color="#FBBF24" stop-opacity="0"/>
          </radialGradient>
        </defs>

        <circle cx="45" cy="60" r="5" fill="#FBBF24" opacity="0.8"/>
        <circle cx="220" cy="70" r="4" fill="#5EEAD4" opacity="0.8"/>
        <circle cx="210" cy="190" r="6" fill="#F472B6" opacity="0.7"/>
        <circle cx="35" cy="180" r="4" fill="#818CF8" opacity="0.8"/>

        <rect x="118" y="60" width="34" height="50" rx="8" fill="#FB7185" transform="rotate(-8 135 85)"/>
        <rect x="150" y="70" width="26" height="42" rx="13" fill="#38BDF8" transform="rotate(6 163 91)"/>
        <path d="M95 75L120 60L135 85L108 100Z" fill="#FBBF24"/>

        <g>
          <path d="M60 110H200L188 200C186 210 178 216 168 216H92C82 216 74 210 72 200L60 110Z" fill="#EDE9FE"/>
          <path d="M60 110H200L195 130H65L60 110Z" fill="#C4B5FD"/>
          <path d="M75 130L84 200M105 130L110 205M155 130L150 205M185 130L176 200" stroke="#C4B5FD" stroke-width="3" stroke-linecap="round"/>
          <path d="M85 110C85 90 100 78 130 78C160 78 175 90 175 110" stroke="#7C3AED" stroke-width="7" stroke-linecap="round"/>
        </g>

        <circle cx="205" cy="55" r="20" fill="#F59E0B"/>
        <text x="205" y="61" font-family="Arial, sans-serif" font-size="16" font-weight="800" fill="#fff" text-anchor="middle">%</text>
    </svg>';
}

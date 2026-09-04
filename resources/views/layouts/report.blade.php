<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $title ?? 'Laporan' }} — {{ config('company.name') }}</title>
<style>
  :root{--ink:#0f172a;--muted:#64748b;--line:#e2e8f0;--line-strong:#0f172a;--accent:#1e3a5f;--thead:#f1f5f9;--paper:#ffffff;--bg:#eef2f7}
  *{box-sizing:border-box}
  html,body{margin:0;padding:0}
  body{font-family:"Inter","Instrument Sans",system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;color:var(--ink);background:var(--bg);-webkit-print-color-adjust:exact;print-color-adjust:exact}
  a{color:inherit}
  .no-print{}
  .print-toolbar{position:sticky;top:0;z-index:40;background:#fff;border-bottom:1px solid var(--line);display:flex;gap:.6rem;align-items:center;justify-content:space-between;padding:.75rem 1rem}
  .print-toolbar__left{display:flex;gap:.6rem;align-items:center}
  .print-toolbar__title{font-size:.85rem;font-weight:600;letter-spacing:.02em}
  .print-toolbar__hint{font-size:.75rem;color:var(--muted);display:none}
  @media(min-width:640px){.print-toolbar__hint{display:block}}
  .btn{appearance:none;border:1px solid var(--line);background:#fff;color:var(--ink);padding:.55rem .95rem;border-radius:.55rem;font-size:.82rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:.45rem;line-height:1}
  .btn:hover{background:#f8fafc}
  .btn--primary{background:var(--accent);color:#fff;border-color:var(--accent)}
  .btn--primary:hover{background:#16304f}
  .btn--ghost{border-color:transparent}
  .paper-wrap{padding:1.25rem .75rem 2.5rem;display:flex;justify-content:center}
  .paper{width:min(210mm,100%);min-height:280mm;background:var(--paper);box-shadow:0 10px 30px rgba(15,23,42,.12),0 1px 3px rgba(15,23,42,.08);padding:14mm 15mm 12mm;position:relative}
  @media(min-width:768px){.paper-wrap{padding:1.75rem 1rem 3rem}}
  @media(max-width:640px){.paper{padding:7mm 5mm 8mm;min-height:auto}}
  .r-header{display:flex;gap:14px;align-items:flex-start;padding-bottom:12px}
  .r-logo{width:56px;height:56px;flex:0 0 56px;border-radius:12px;background:var(--accent);color:#fff;display:grid;place-items:center;font-weight:800;letter-spacing:.04em;font-size:1.05rem;overflow:hidden}
  .r-logo img{width:100%;height:100%;object-fit:contain;background:#fff}
  .r-company{flex:1;min-width:0}
  .r-company__name{font-size:15.5pt;font-weight:800;letter-spacing:.02em;line-height:1.05;text-transform:uppercase;color:var(--accent)}
  .r-company__legal{font-size:8.2pt;color:var(--muted);margin-top:2px;letter-spacing:.02em}
  .r-company__tagline{font-size:7.8pt;color:var(--accent);font-weight:700;letter-spacing:.08em;text-transform:uppercase;margin-top:3px}
  .r-contacts{margin-top:6px;display:flex;flex-wrap:wrap;gap:4px 14px;font-size:7.6pt;color:var(--muted);line-height:1.45}
  .r-contacts span{white-space:nowrap}
  .r-rule{height:2.6px;background:var(--accent);margin-top:10px;border-radius:999px}
  .r-rule--thin{height:1px;background:var(--line);margin-top:3px}
  .r-title-block{text-align:center;margin:16px 0 10px}
  .r-title{font-size:14pt;font-weight:800;letter-spacing:.04em;text-transform:uppercase;line-height:1.2;margin:0}
  .r-subtitle{font-size:9pt;color:var(--muted);margin-top:4px;letter-spacing:.02em}
  .r-underline{width:56px;height:2.5px;background:var(--accent);margin:9px auto 0;border-radius:999px}
  .meta-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px 14px;background:#f8fafc;border:1px solid var(--line);border-radius:10px;padding:10px 12px;margin:10px 0 12px}
  @media(max-width:640px){.meta-grid{grid-template-columns:1fr}}
  .meta-item{display:flex;flex-direction:column;gap:2px;min-width:0}
  .meta-k{font-size:6.9pt;font-weight:700;letter-spacing:.09em;text-transform:uppercase;color:var(--muted);line-height:1}
  .meta-v{font-size:8.8pt;font-weight:600;line-height:1.35;word-break:break-word}
  .meta-v small{font-weight:400;color:var(--muted)}
  .r-content{margin-top:4px}
  table.r-table{width:100%;border-collapse:collapse;font-size:8.4pt;line-height:1.45}
  .r-table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;border:1px solid var(--line);border-radius:10px}
  .r-table th{background:var(--thead);font-size:7.35pt;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:#334155;text-align:left;padding:8px 8px;border-bottom:1px solid var(--line);white-space:nowrap}
  .r-table td{padding:7px 8px;border-bottom:1px solid #eef2f7;vertical-align:top}
  .r-table tbody tr:last-child td{border-bottom:none}
  .r-table tbody tr:nth-child(even) td{background:#fcfdff}
  .r-table .num{text-align:right;white-space:nowrap}
  .r-table .center{text-align:center}
  .r-table tfoot td{background:#f8fafc;font-weight:700;border-top:1.5px solid var(--line)}
  .badge{display:inline-block;padding:2px 7px;border-radius:999px;font-size:7.2pt;font-weight:700;letter-spacing:.04em;border:1px solid var(--line);background:#fff;white-space:nowrap}
  .badge--success{background:#f0fdf4;border-color:#bbf7d0;color:#166534}
  .badge--warning{background:#fffbeb;border-color:#fde68a;color:#92400e}
  .badge--danger{background:#fef2f2;border-color:#fecaca;color:#991b1b}
  .badge--info{background:#eff6ff;border-color:#bfdbfe;color:#1e40af}
  .badge--gray{background:#f8fafc;color:#475569}
  .kv{display:grid;grid-template-columns:160px 1fr;gap:6px 12px;font-size:8.8pt;padding:10px 0}
  @media(max-width:640px){.kv{grid-template-columns:1fr}}
  .kv dt{color:var(--muted);font-size:7.2pt;font-weight:700;letter-spacing:.06em;text-transform:uppercase;padding-top:2px}
  .kv dd{margin:0;font-weight:600;word-break:break-word}
  .r-note{margin-top:10px;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:8px 10px;font-size:7.9pt;color:#92400e;line-height:1.5}
  .r-sigs{margin-top:18px;display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
  @media(max-width:640px){.r-sigs{grid-template-columns:1fr;gap:14px}}
  .sig{font-size:8.2pt;text-align:center}
  .sig__role{font-size:7pt;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:var(--muted)}
  .sig__box{margin-top:42px;border-top:1px solid var(--ink);padding-top:6px;font-weight:700}
  .sig__name{font-size:7pt;color:var(--muted);font-weight:400;margin-top:2px}
  .r-footer{margin-top:16px;border-top:1px solid var(--line);padding-top:8px;display:flex;flex-wrap:wrap;gap:6px 12px;justify-content:space-between;font-size:7pt;color:var(--muted);line-height:1.5}
  .r-footer strong{color:var(--ink)}
  .watermark{position:absolute;inset:0;display:grid;place-items:center;pointer-events:none;opacity:.035;font-size:74pt;font-weight:900;letter-spacing:.08em;transform:rotate(-18deg);text-transform:uppercase}
  .page-break{break-after:page}
  @media print{
    @page{size:A4;margin:12mm 11mm 14mm 11mm}
    body{background:#fff}
    .no-print{display:none !important}
    .paper-wrap{padding:0}
    .paper{box-shadow:none;width:100%;min-height:auto;padding:0}
    .r-table-wrap{border:none;border-radius:0;overflow:visible}
    thead{display:table-header-group}
    tfoot{display:table-footer-group}
    tr{break-inside:avoid}
  }
</style>
@stack('head')
</head>
<body>
<div class="print-toolbar no-print">
  <div class="print-toolbar__left">
    <div class="print-toolbar__title">Print Preview</div>
    <div class="print-toolbar__hint">Pratinjau A4 — gunakan Cetak untuk simpan PDF</div>
  </div>
  <div style="display:flex;gap:.5rem;align-items:center">
    <button class="btn btn--ghost" onclick="window.close(); if(window.history.length>1) history.back();">Tutup</button>
    <button class="btn btn--primary" onclick="window.print()">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
      Cetak / Simpan PDF
    </button>
  </div>
</div>

<div class="paper-wrap">
  <div class="paper">
    @if(($watermark ?? false))
      <div class="watermark">{{ config('company.name') }}</div>
    @endif

    <header class="r-header">
      <div class="r-logo">
        @if(config('company.logo'))
          <img src="{{ config('company.logo') }}" alt="Logo">
        @else
          {{ strtoupper(substr(preg_replace('/[^A-Z]/','', strtoupper(config('company.name'))),0,2) ?: 'EM') }}
        @endif
      </div>
      <div class="r-company">
        <div class="r-company__name">{{ config('company.name') }}</div>
        <div class="r-company__legal">{{ config('company.legal_name') }}</div>
        @if(config('company.tagline'))<div class="r-company__tagline">{{ config('company.tagline') }}</div>@endif
        <div class="r-contacts">
          <span>{{ config('company.address') }}</span>
          <span>Tel: {{ config('company.phone') }}</span>
          <span>Email: {{ config('company.email') }}</span>
          @if(config('company.website'))<span>Web: {{ config('company.website') }}</span>@endif
        </div>
      </div>
    </header>
    <div class="r-rule"></div>
    <div class="r-rule--thin"></div>

    <div class="r-title-block">
      <h1 class="r-title">{{ $title ?? 'Laporan' }}</h1>
      @if(!empty($subtitle))<div class="r-subtitle">{{ $subtitle }}</div>@endif
      <div class="r-underline"></div>
    </div>

    @if(!empty($meta))
      <div class="meta-grid">
        @foreach($meta as $m)
          <div class="meta-item">
            <div class="meta-k">{{ $m['label'] }}</div>
            <div class="meta-v">{!! $m['value'] !!}</div>
          </div>
        @endforeach
      </div>
    @endif

    <div class="r-content">
      {{ $slot ?? '' }}
      @yield('report_content')
    </div>

    @if(!empty($signatures ?? null))
      <div class="r-sigs">
        @foreach($signatures as $sig)
          <div class="sig">
            <div class="sig__role">{{ $sig['role'] ?? 'Penanggung Jawab' }}</div>
            <div style="margin-top:6px;font-size:7pt;color:var(--muted)">{{ $sig['place_date'] ?? '' }}</div>
            <div class="sig__box">{{ $sig['name'] ?? '...........................' }}</div>
            <div class="sig__name">{{ $sig['note'] ?? '' }}</div>
          </div>
        @endforeach
      </div>
    @endif

    <div class="r-footer">
      <div>
        Dokumen resmi <strong>{{ config('company.name') }}</strong> &middot; Dicetak: {{ $printedAt ?? now()->translatedFormat('d F Y H:i') }} WIB
        @if(!empty($printedBy)) &middot; Oleh: {{ $printedBy }} @endif
        @if(!empty($docNo)) &middot; No: {{ $docNo }} @endif
      </div>
      <div>Halaman <span class="page-num" style="font-weight:700">1</span> &middot; Arsip Perusahaan — Confidential</div>
    </div>
  </div>
</div>

<script>
  if(new URLSearchParams(location.search).has('auto')) setTimeout(()=>window.print(), 350);
  document.addEventListener('keydown', e=>{ if((e.ctrlKey||e.metaKey)&&e.key==='p'){ e.preventDefault(); window.print(); }});
</script>
@stack('scripts')
</body>
</html>

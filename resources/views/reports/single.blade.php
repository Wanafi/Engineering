@extends('layouts.report', ['title' => $title, 'subtitle' => $subtitle, 'meta' => $meta, 'printedBy' => $printedBy, 'printedAt' => $printedAt, 'docNo' => $docNo, 'signatures' => $signatures ?? \App\Support\ReportBuilder::signatures(), 'watermark' => true])

@section('report_content')
  @foreach($sections as $sec)
    <div style="margin-top:14px; border:1px solid var(--line); border-radius:10px; overflow:hidden">
      <div style="background:var(--thead); padding:7px 12px; font-weight:800; font-size:7.8pt; letter-spacing:.06em; text-transform:uppercase; color:var(--accent); border-bottom:1px solid var(--line)">
        {{ $sec['title'] }}
      </div>
      <dl class="kv" style="padding:10px 12px; margin:0">
        @foreach($sec['fields'] as $f)
          <dt>{{ $f[0] }}</dt>
          <dd>
            @if(in_array(strtolower($f[0]), ['status','priority','tipe']))
              {!! \App\Support\ReportBuilder::badge($f[1]) !!}
            @else
              {!! $f[1] !!}
            @endif
          </dd>
        @endforeach
      </dl>
    </div>
  @endforeach

  @if(!empty($table))
    <div style="margin-top:16px">
      <div style="font-size:8.5pt; font-weight:800; text-transform:uppercase; letter-spacing:.05em; color:var(--accent); margin-bottom:6px">Detail Item / Jawaban</div>
      <div class="r-table-wrap">
        <table class="r-table">
          <thead>
            <tr>
              @foreach($table['columns'] as $c)
                <th>{{ $c }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach($table['rows'] as $r)
              <tr>
                @foreach($r as $val)
                  <td>{!! $val !!}</td>
                @endforeach
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif

@endsection

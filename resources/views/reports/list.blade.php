@extends('layouts.report', ['title' => $title, 'subtitle' => $subtitle, 'meta' => $meta, 'printedBy' => $printedBy, 'printedAt' => $printedAt, 'docNo' => $docNo, 'signatures' => $signatures ?? \App\Support\ReportBuilder::signatures(), 'watermark' => true])

@section('report_content')
  @if(!empty($rows) && count($rows) > 0)
    <div class="r-table-wrap" style="margin-top:10px">
      <table class="r-table">
        <thead>
          <tr>
            @foreach($columns as $col)
              <th @if(in_array(strtolower($col), ['biaya','jumlah','amount'])) class="num" @endif>{{ $col }}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach($rows as $row)
            <tr>
              @foreach($row as $idx => $cell)
                @php
                  $isNum = in_array(strtolower($columns[$idx] ?? ''), ['biaya','jumlah','amount']);
                  $isBadge = in_array(strtolower($columns[$idx] ?? ''), ['status','priority','tipe']);
                @endphp
                <td @if($isNum) class="num" @endif>
                  @if($isBadge)
                    {!! \App\Support\ReportBuilder::badge($cell) !!}
                  @else
                    {!! $cell !!}
                  @endif
                </td>
              @endforeach
            </tr>
          @endforeach
        </tbody>
        @if(!empty($summary))
          <tfoot>
            <tr>
              <td colspan="{{ count($columns) - 1 }}" style="text-align:right; font-weight:700;">{{ $summary[0] }}:</td>
              <td class="num" style="font-weight:800; font-size:9pt;">{{ $summary[1] }}</td>
            </tr>
          </tfoot>
        @endif
      </table>
    </div>
  @else
    <div style="padding:2rem; text-align:center; color:var(--muted); border:1px dashed var(--line); border-radius:10px; margin-top:12px;">
      Tidak ada data laporan yang ditemukan untuk filter/kriteria yang dipilih.
    </div>
  @endif

@endsection

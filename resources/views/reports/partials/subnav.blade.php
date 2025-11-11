@php($subnavItems = [
  ['route' => 'reports.loans', 'label' => 'Peminjaman', 'desc' => 'Rekap barang keluar', 'active' => request()->routeIs('reports.loans')],
  ['route' => 'reports.returns', 'label' => 'Pengembalian', 'desc' => 'Barang kembali', 'active' => request()->routeIs('reports.returns')],
  ['route' => 'reports.losses', 'label' => 'Kehilangan', 'desc' => 'Barang belum kembali', 'active' => request()->routeIs('reports.losses')],
])
<section class="report-subnav">
  @foreach($subnavItems as $item)
    <a href="{{ route($item['route']) }}" class="report-subnav__link {{ $item['active'] ? 'is-active' : '' }}">
      <span>{{ $item['label'] }}</span>
      <small>{{ $item['desc'] }}</small>
    </a>
  @endforeach
</section>

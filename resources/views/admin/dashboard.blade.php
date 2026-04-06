@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    body { background: linear-gradient(180deg, #F1F8E9 0%, #ffffff 100%); }

    .glass {
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(139,195,74,0.2);
        box-shadow: 0 8px 30px rgba(139,195,74,0.08);
        border-radius: 14px;
        padding: 20px;
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .glass:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 40px rgba(139,195,74,0.15);
    }

    .stat-icon{
        width:56px;
        height:56px;
        border-radius:12px;
        display:flex;
        align-items:center;
        justify-content:center;
        background: linear-gradient(135deg, rgba(139,195,74,0.15), rgba(139,195,74,0.05));
        color:#558B2F;
        font-size:22px;
    }

    .title-sm{
        color:#558B2F;
        font-weight:700;
    }

    .muted{
        color:#6b7280;
    }

    @media (max-width:768px){
        .grid-3{
            grid-template-columns:repeat(1,minmax(0,1fr));
        }
    }
</style>

<div class="container mx-auto">

    <!-- GRID SUMMARY -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 grid-3 mb-8">

        <div class="glass flex items-center justify-between">
            <div>
                <div class="title-sm">Total Produk</div>
                <div class="text-3xl font-bold text-green-800 mt-2">{{ $totalProducts }}</div>
            </div>
            <div class="stat-icon"><i class="fa fa-box"></i></div>
        </div>

        <div class="glass flex items-center justify-between">
            <div>
                <div class="title-sm">Total User</div>
                <div class="text-3xl font-bold text-green-800 mt-2">{{ $totalUsers }}</div>
            </div>
            <div class="stat-icon"><i class="fa fa-users"></i></div>
        </div>

        <div class="glass flex items-center justify-between">
            <div>
                <div class="title-sm">Transaksi Kasir (Offline)</div>
                <div class="text-3xl font-bold text-green-800 mt-2">{{ $totalTransactions }}</div>
            </div>
            <div class="stat-icon"><i class="fa fa-shopping-cart"></i></div>
        </div>

        <div class="glass flex items-center justify-between">
            <div>
                <div class="title-sm">Order Online</div>
                <div class="text-3xl font-bold text-green-800 mt-2">{{ $totalOrders }}</div>
            </div>
            <div class="stat-icon"><i class="fa fa-globe"></i></div>
        </div>

        <div class="glass flex items-center justify-between">
            <div>
                <div class="title-sm">Penghasilan Hari Ini (Offline)</div>
                <div class="text-3xl font-bold text-green-800 mt-2">
                    Rp {{ number_format($offlineIncomeToday,0,',','.') }}
                </div>
            </div>
            <div class="stat-icon"><i class="fa fa-wallet"></i></div>
        </div>

        <div class="glass flex items-center justify-between">
            <div>
                <div class="title-sm">Penghasilan Hari Ini (Online)</div>
                <div class="text-3xl font-bold text-green-800 mt-2">
                    Rp {{ number_format($onlineIncomeToday,0,',','.') }}
                </div>
            </div>
            <div class="stat-icon"><i class="fa fa-credit-card"></i></div>
        </div>

        <!-- CARD DENDA -->
        <div class="glass flex items-center justify-between">
            <div>
                <div class="title-sm">Pendapatan Denda Hari Ini</div>
                <div class="text-3xl font-bold text-green-800 mt-2">
                    Rp {{ number_format($lateFeeIncomeToday,0,',','.') }}
                </div>
            </div>
            <div class="stat-icon"><i class="fa fa-exclamation-circle"></i></div>
        </div>

    </div>

    <!-- DATA HOLDER -->
    <div id="chart-data-holder"
         data-labels="{{ implode('|',$chartLabels) }}"
         data-offline="{{ implode('|',$offlineData) }}"
         data-online="{{ implode('|',$onlineData) }}"
         data-latefee="{{ implode('|',$lateFeeData) }}"
         style="display:none"></div>

    <!-- GRAFIK -->
    <div class="glass mb-8">

        <div class="flex items-center justify-between mb-3">
            <div>
                <div class="text-lg font-semibold text-green-800">
                    Grafik Penjualan 7 Hari
                </div>
                <div class="muted text-sm">
                    Offline vs Online vs Denda
                </div>
            </div>

            <div class="text-sm muted">
                Update: {{ now()->format('d M Y H:i') }}
            </div>
        </div>

        <div style="height:320px">
            <canvas id="salesChart"></canvas>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded',function(){

    const holder = document.getElementById('chart-data-holder');

    const labels  = holder.dataset.labels.split('|');

    const offline = holder.dataset.offline.split('|').map(v=>Number(v||0));
    const online  = holder.dataset.online.split('|').map(v=>Number(v||0));
    const latefee = holder.dataset.latefee.split('|').map(v=>Number(v||0));

    const ctx = document.getElementById('salesChart').getContext('2d');

    const gradientOffline = ctx.createLinearGradient(0,0,0,300);
    gradientOffline.addColorStop(0,'rgba(85,139,47,0.2)');
    gradientOffline.addColorStop(1,'rgba(85,139,47,0.03)');

    const gradientOnline = ctx.createLinearGradient(0,0,0,300);
    gradientOnline.addColorStop(0,'rgba(139,195,74,0.2)');
    gradientOnline.addColorStop(1,'rgba(139,195,74,0.03)');

    new Chart(ctx,{
        type:'line',
        data:{
            labels:labels,
            datasets:[

                {
                    label:'Offline (Rp)',
                    data:offline,
                    borderColor:'#558B2F',
                    backgroundColor:gradientOffline,
                    fill:true,
                    borderWidth:3,
                    tension:0.36
                },

                {
                    label:'Online (Rp)',
                    data:online,
                    borderColor:'#8BC34A',
                    backgroundColor:gradientOnline,
                    fill:true,
                    borderWidth:3,
                    tension:0.36
                },

                {
                    label:'Denda (Rp)',
                    data:latefee,
                    borderColor:'#ef4444',
                    backgroundColor:'rgba(239,68,68,0.1)',
                    fill:true,
                    borderWidth:3,
                    tension:0.36
                }

            ]
        },

        options:{
            responsive:true,
            maintainAspectRatio:false,

            plugins:{
                tooltip:{
                    callbacks:{
                        label:function(context){
                            const v = Number(context.raw)||0;
                            return context.dataset.label+
                            ': Rp '+v.toLocaleString('id-ID');
                        }
                    }
                }
            },

            scales:{
                x:{
                    ticks:{color:'#558B2F'},
                    grid:{display:false}
                },
                y:{
                    ticks:{
                        callback:v=>'Rp '+Number(v).toLocaleString('id-ID'),
                        color:'#558B2F'
                    },
                    grid:{
                        color:'rgba(139,195,74,0.1)'
                    }
                }
            }

        }

    });

});

</script>

@endsection
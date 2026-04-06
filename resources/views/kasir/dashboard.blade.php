@extends('layouts.kasir')

@section('title', 'Dashboard')

@section('content')
<style>
    .glass-purple {
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(168,85,247,0.2);
        box-shadow: 0 8px 30px rgba(168,85,247,0.08);
        border-radius: 14px;
        padding: 20px;
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .glass-purple:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 40px rgba(168,85,247,0.15);
    }

    .stat-icon-purple {
        width:56px;
        height:56px;
        border-radius:12px;
        display:flex;
        align-items:center;
        justify-content:center;
        background: linear-gradient(135deg, rgba(168,85,247,0.15), rgba(168,85,247,0.05));
        color:#7c3aed;
        font-size:22px;
    }

    .title-sm-purple {
        color:#7c3aed;
        font-weight:700;
    }

    .muted {
        color:#6b7280;
    }
</style>

<div class="container mx-auto">

    <!-- GRID SUMMARY -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-8">

        <div class="glass-purple flex items-center justify-between">
            <div>
                <div class="title-sm-purple">Total Transaksi Hari Ini</div>
                <div class="text-3xl font-bold text-purple-800 mt-2">{{ $totalHariIni }}</div>
            </div>
            <div class="stat-icon-purple"><i class="fa fa-shopping-cart"></i></div>
        </div>

        <div class="glass-purple flex items-center justify-between">
            <div>
                <div class="title-sm-purple">Transaksi Sukses</div>
                <div class="text-3xl font-bold text-purple-800 mt-2">{{ $suksesHariIni }}</div>
            </div>
            <div class="stat-icon-purple"><i class="fa fa-check-circle"></i></div>
        </div>

        <div class="glass-purple flex items-center justify-between">
            <div>
                <div class="title-sm-purple">Transaksi Pending</div>
                <div class="text-3xl font-bold text-purple-800 mt-2">{{ $pendingHariIni }}</div>
            </div>
            <div class="stat-icon-purple"><i class="fa fa-hourglass"></i></div>
        </div>

    </div>

    <!-- DATA HOLDER -->
    <div id="chart-data-holder"
         data-labels="{{ implode('|',$revenueData['dates']) }}"
         data-offline="{{ implode('|',$revenueData['offline']) }}"
         data-online="{{ implode('|',$revenueData['online']) }}"
         data-latefee="{{ implode('|',$revenueData['lateFees']) }}"
         style="display:none"></div>

    <!-- GRAFIK -->
    <div class="glass-purple mb-8">

        <div class="flex items-center justify-between mb-3">
            <div>
                <div class="text-lg font-semibold text-purple-800">
                    Grafik Penghasilan 7 Hari Terakhir
                </div>
                <div class="muted text-sm">
                    Online vs Offline vs Denda
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
    gradientOffline.addColorStop(0,'rgba(147,51,234,0.2)');
    gradientOffline.addColorStop(1,'rgba(147,51,234,0.03)');

    const gradientOnline = ctx.createLinearGradient(0,0,0,300);
    gradientOnline.addColorStop(0,'rgba(168,85,247,0.2)');
    gradientOnline.addColorStop(1,'rgba(168,85,247,0.03)');

    const gradientLateFee = ctx.createLinearGradient(0,0,0,300);
    gradientLateFee.addColorStop(0,'rgba(239,68,68,0.2)');
    gradientLateFee.addColorStop(1,'rgba(239,68,68,0.03)');

    new Chart(ctx,{
        type:'line',
        data:{
            labels:labels,
            datasets:[

                {
                    label:'Offline (Rp)',
                    data:offline,
                    borderColor:'#7c3aed',
                    backgroundColor:gradientOffline,
                    fill:true,
                    borderWidth:3,
                    tension:0.36
                },

                {
                    label:'Online (Rp)',
                    data:online,
                    borderColor:'#a855f7',
                    backgroundColor:gradientOnline,
                    fill:true,
                    borderWidth:3,
                    tension:0.36
                },

                {
                    label:'Denda (Rp)',
                    data:latefee,
                    borderColor:'#ef4444',
                    backgroundColor:gradientLateFee,
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
                    ticks:{color:'#7c3aed'},
                    grid:{display:false}
                },
                y:{
                    ticks:{
                        callback:v=>'Rp '+Number(v).toLocaleString('id-ID'),
                        color:'#7c3aed'
                    },
                    grid:{
                        color:'rgba(168,85,247,0.1)'
                    }
                }
            }

        }

    });

});

</script>
@endsection

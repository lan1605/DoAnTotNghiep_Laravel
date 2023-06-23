@extends('admin.layout.index')

@section('content')
<main class="page-content">
    <!--breadcrumb-->
    @include('layouts.breadcrumb')
    <!--end breadcrumb-->
    @include('layouts.notificationLogin')
    <div class="row">
        {{-- {{dd($thongtin)}} --}}
        @foreach ($thongtin as $item=>$value)
        <div class="col-12 col-lg-12">
            <div class="card shadow-sm border-0 overflow-hidden">
                <div class="card-body">
                    <div class="border p-4 rounded">
                        <div class="row mb-3">
                            <div class="col-6">
                                @php
                                    $user = App\Models\User::find($item);
                                @endphp
                                <p class="py-0 mb-1"><strong>Họ tên: </strong>{{$user->name}}</p>
                                {{-- <hr style="margin-top: 0.5rem"> --}}
                            </div>
                            <div class="col-6">
                                <p class="py-0 mb-1"><strong>Bài tập: </strong>{{$baitap->ten_baitap}}</p>
                                {{-- <hr style="margin-top: 0.5rem"> --}}
                            </div>
                            <div class="col-6">
                                <p class="py-0 mb-1"><strong>Bài học: </strong>
                                    @php
                                        $baihoc = App\Models\BaiHoc::find($baitap->id_baihoc);
                                    @endphp
                                    {{$baihoc->ten_baihoc}}
                                </p>
                                {{-- <hr style="margin-top: 0.5rem"> --}}
                            </div>
                            <div class="col-6">
                                <p class="py-0 mb-1"><strong>Chủ đề: </strong>
                                    @php
                                        $chude = App\Models\ChuDe::find($baihoc->id_chude);
                                    @endphp
                                    {{$chude->ten_chude}}
                                </p>
                                {{-- <hr style="margin-top: 0.5rem"> --}}
                            </div>
                            <div class="col-6">
                                <p class="py-0 mb-1"><strong>Số lần làm bài: </strong>{{count($value)}}</p>
                                {{-- <hr style="margin-top: 0.5rem"> --}}
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p class="fs-5" >Chi tiết làm bài</p>
        <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-primary" role="tablist">
                        @php
                            $dem = 0;
                            $dem_content = 0;
                        @endphp
                        @foreach ($value as $item_ct)
                        <?php $dem = $dem + 1?>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{$dem == 1 ? "active" : ""}}" data-bs-toggle="tab" href="#chitiet-{{$dem}}" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-title">Lần {{$dem}}</div>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    <div class="tab-content py-3">
                        @foreach ($value as $item_ct)
                        @php
                            $dem_content = $dem_content +1;
                        @endphp   
                        <div class="tab-pane fade {{$dem_content==1 ? 'active show' :''}}" id="chitiet-{{$dem_content}}" role="tabpanel">
                            @php
                                $danhsach = App\Models\LamBaiTap::where('id_baitap', $baitap->id_baitap)->where('created_at', $item_ct->created_at)->where('id_hocvien',$user->id)->take($baitap->soluong_cauhoi)->orderBy('updated_at', 'DESC')->orderBy('id_cauhoi', 'ASC')->get();
                                $arrIDcauhoi = [];

                                foreach ($danhsach as $ds){
                                    $arrIDcauhoi[] = $ds->id_cauhoi;
                                }
                                $cauhoi = App\Models\CauHoi::whereIn('id', $arrIDcauhoi)->get();
                                $ketqua = App\Models\KetQua::where('id_baitap', $baitap->id_baitap)->where('id_hocvien', $user->id)->where('created_at', $item_ct->created_at)->orderBy('created_at', 'DESC')->first();
                                // dd($cauhoi);
                            @endphp
                            <div class="row">
                                <div class="col-12 col-lg-8 ">
                                    <div >
                                        <div class="card-header py-2 " >
                                            <h2 class="fw-500 text-primary">Danh sách câu trả lời</h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-2 mt-1">
                                                <div class="col-12" id="contents">
                                                    @if (count($cauhoi)==0)
                                                        {{'Không có thông tin hiển thị'}}
                                                    @else
                                                    <?php $dem_ds = 0;?>
                                                        @foreach ($cauhoi as $item)
                                                            <?php $dem_ds = $dem_ds +1; ?>
                                                            @foreach ($danhsach as $item_lbt)
                                                                @if ($item->id ===$item_lbt->id_cauhoi)
                                                                @if ($dem / 2 == 0)
                                                                <div class="mt-2 " id="quest-{{$item->id}}">
                                                                    <h5><strong>Câu {{$dem}}</strong>: {{$item->noi_dung}}</h5>
                                                                    <div class="question-answer fs-6">
                                                                        <div class="answer">
                                                                            @if ((trim($item_lbt->dapan_hocvien)===trim($item->dap_an_dung)) && (trim($item_lbt->dapan_hocvien)===trim($item->dap_an_1)))
                                                                                <input type="radio" name="" id="" checked value="{{trim($item->dap_an_1)}}" disabled > <span class="text-success">A. {!! $item->dap_an_1 !!}</span>
                                                                            @else
                                                                                @if ((trim($item_lbt->dapan_hocvien)!==trim($item->dap_an_dung)) && (trim($item_lbt->dapan_hocvien)===trim($item->dap_an_1)))
                                                                                    <input type="radio" name="" id="" checked value="{{trim($item->dap_an_1)}}" disabled > <span class="text-danger">A. {!! $item->dap_an_1 !!}</span>
                                                                                @else
                                                                                    @if ((trim($item->dap_an_1)===trim($item->dap_an_dung)))
                                                                                        <input type="radio" name="" id="" checked value="{{trim($item->dap_an_1)}}" disabled > <span class="text-success">A. {!! $item->dap_an_1 !!}</span>
                                                                                    @else
                                                                                        <input type="radio" name="" id="" value="{{trim($item->dap_an_1)}}" disabled > A. {!! $item->dap_an_1 !!}
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                        <div class="answer">
                                                                            @if ((trim($item_lbt->dapan_hocvien)===trim($item->dap_an_dung)) && (trim($item_lbt->dapan_hocvien)===trim($item->dap_an_2)))
                                                                                <input type="radio" name="" id="" checked value="{{trim($item->dap_an_2)}}" disabled > <span class="text-success">B. {!! $item->dap_an_2 !!}</span>
                                                                            @else
                                                                                @if ((trim($item_lbt->dapan_hocvien)!==trim($item->dap_an_dung)) && (trim($item_lbt->dapan_hocvien)===trim($item->dap_an_2)))
                                                                                    <input type="radio" name="" id="" checked value="{{trim($item->dap_an_2)}}" disabled > <span class="text-danger">B. {!! $item->dap_an_2 !!}</span>
                                                                                @else
                                                                                    @if ((trim($item->dap_an_2)===trim($item->dap_an_dung)))
                                                                                        <input type="radio" name="" id="" checked value="{{trim($item->dap_an_2)}}" disabled > <span class="text-success">B. {!! $item->dap_an_2 !!}</span>
                                                                                    @else
                                                                                        <input type="radio" name="" id="" value="{{trim($item->dap_an_2)}}" disabled > B. {!! $item->dap_an_2 !!}
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                        <div class="answer">
                                                                            @if ((trim($item_lbt->dapan_hocvien)===trim($item->dap_an_dung)) && (trim($item_lbt->dapan_hocvien)===trim($item->dap_an_3)))
                                                                                <input type="radio" name="" id="" checked value="{{trim($item->dap_an_3)}}" disabled > <span class="text-success">C. {!! $item->dap_an_3 !!}</span>
                                                                            @else
                                                                                @if ((trim($item_lbt->dapan_hocvien)!==trim($item->dap_an_dung)) && (trim($item_lbt->dapan_hocvien)===trim($item->dap_an_3)))
                                                                                    <input type="radio" name="" id="" checked value="{{trim($item->dap_an_3)}}" disabled > <span class="text-danger">C. {!! $item->dap_an_3 !!}</span>
                                                                                @else
                                                                                    @if ((trim($item->dap_an_3)===trim($item->dap_an_dung)))
                                                                                        <input type="radio" name="" id="" checked value="{{trim($item->dap_an_3)}}" disabled > <span class="text-success">C. {!! $item->dap_an_3 !!}</span>
                                                                                    @else
                                                                                        <input type="radio" name="" id="" value="{{trim($item->dap_an_3)}}" disabled > C. {!! $item->dap_an_3 !!}
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                        <div class="answer">
                                                                            @if ((trim($item_lbt->dapan_hocvien)===trim($item->dap_an_dung)) && (trim($item_lbt->dapan_hocvien)===trim($item->dap_an_4)))
                                                                                <input type="radio" name="" id="" checked value="{{trim($item->dap_an_4)}}" disabled > <span class="text-success">D. {!! $item->dap_an_4 !!}</span>
                                                                            @else
                                                                                @if ((trim($item_lbt->dapan_hocvien)!==trim($item->dap_an_dung)) && (trim($item_lbt->dapan_hocvien)===trim($item->dap_an_4)))
                                                                                    <input type="radio" name="" id="" checked value="{{trim($item->dap_an_4)}}" disabled > <span class="text-danger">D. {!! $item->dap_an_4 !!}</span>
                                                                                @else
                                                                                    @if ((trim($item->dap_an_4)===trim($item->dap_an_dung)))
                                                                                        <input type="radio" name="" id="" checked value="{{trim($item->dap_an_4)}}" disabled > <span class="text-success">D. {!! $item->dap_an_4 !!}</span>
                                                                                    @else
                                                                                        <input type="radio" name="" id="" value="{{trim($item->dap_an_4)}}" disabled > D. {!! $item->dap_an_4 !!}
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="alert border-0 bg-light-success alert-dismissible fade show py-2">
                                                                        <strong>Đáp án đúng:</strong> {!! $item->dap_an_dung !!}
                                                                    </div>
                                                                </div>
                                                                @else
                                                                <div class="mt-2 " id="quest-{{$item->id}}">
                                                                    <h5><strong>Câu {{$dem}}</strong>: {{$item->noi_dung}}</h5>
                                                                    <div class="question-answer fs-6">
                                                                        <div class="answer">
                                                                            @if ((trim($item_lbt->dapan_hocvien)===trim($item->dap_an_dung)) && (trim($item_lbt->dapan_hocvien)===trim($item->dap_an_3)))
                                                                                <input type="radio" name="" id="" checked value="{{trim($item->dap_an_3)}}" disabled > <span class="text-success">A. {!! $item->dap_an_3 !!}</span>
                                                                            @else
                                                                                @if ((trim($item_lbt->dapan_hocvien)!==trim($item->dap_an_dung)) && (trim($item_lbt->dapan_hocvien)===trim($item->dap_an_3)))
                                                                                    <input type="radio" name="" id="" checked value="{{trim($item->dap_an_3)}}" disabled > <span class="text-danger">A. {!! $item->dap_an_3 !!}</span>
                                                                                @else
                                                                                    @if ((trim($item->dap_an_3)===trim($item->dap_an_dung)))
                                                                                        <input type="radio" name="" id="" checked value="{{trim($item->dap_an_3)}}" disabled > <span class="text-success">A. {!! $item->dap_an_3 !!}</span>
                                                                                    @else
                                                                                        <input type="radio" name="" id="" value="{{trim($item->dap_an_3)}}" disabled > A. {!! $item->dap_an_3 !!}
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                        <div class="answer">
                                                                            @if ((trim($item_lbt->dapan_hocvien)===trim($item->dap_an_dung)) && (trim($item_lbt->dapan_hocvien)===trim($item->dap_an_2)))
                                                                                <input type="radio" name="" id="" checked value="{{trim($item->dap_an_2)}}" disabled > <span class="text-success">B. {!! $item->dap_an_2 !!}</span>
                                                                            @else
                                                                                @if ((trim($item_lbt->dapan_hocvien)!==trim($item->dap_an_dung)) && (trim($item_lbt->dapan_hocvien)===trim($item->dap_an_2)))
                                                                                    <input type="radio" name="" id="" checked value="{{trim($item->dap_an_2)}}" disabled > <span class="text-danger">B. {!! $item->dap_an_2 !!}</span>
                                                                                @else
                                                                                    @if ((trim($item->dap_an_2)===trim($item->dap_an_dung)))
                                                                                        <input type="radio" name="" id="" checked value="{{trim($item->dap_an_2)}}" disabled > <span class="text-success">B. {!! $item->dap_an_2 !!}</span>
                                                                                    @else
                                                                                        <input type="radio" name="" id="" value="{{trim($item->dap_an_2)}}" disabled > B. {!! $item->dap_an_2 !!}
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                        <div class="answer">
                                                                            @if ((trim($item_lbt->dapan_hocvien)===trim($item->dap_an_dung)) && (trim($item_lbt->dapan_hocvien)===trim($item->dap_an_1)))
                                                                                <input type="radio" name="" id="" checked value="{{trim($item->dap_an_1)}}" disabled > <span class="text-success">C. {!! $item->dap_an_1 !!}</span>
                                                                            @else
                                                                                @if ((trim($item_lbt->dapan_hocvien)!==trim($item->dap_an_dung)) && (trim($item_lbt->dapan_hocvien)===trim($item->dap_an_1)))
                                                                                    <input type="radio" name="" id="" checked value="{{trim($item->dap_an_1)}}" disabled > <span class="text-danger">C. {!! $item->dap_an_1 !!}</span>
                                                                                @else
                                                                                    @if ((trim($item->dap_an_1)===trim($item->dap_an_dung)))
                                                                                        <input type="radio" name="" id="" checked value="{{trim($item->dap_an_1)}}" disabled > <span class="text-success">C. {!! $item->dap_an_1 !!}</span>
                                                                                    @else
                                                                                        <input type="radio" name="" id="" value="{{trim($item->dap_an_1)}}" disabled > C. {!! $item->dap_an_1 !!}
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                        <div class="answer">
                                                                            @if ((trim($item_lbt->dapan_hocvien)===trim($item->dap_an_dung)) && (trim($item_lbt->dapan_hocvien)===trim($item->dap_an_4)))
                                                                                <input type="radio" name="" id="" checked value="{{trim($item->dap_an_4)}}" disabled > <span class="text-success">D. {!! $item->dap_an_4 !!}</span>
                                                                            @else
                                                                                @if ((trim($item_lbt->dapan_hocvien)!==trim($item->dap_an_dung)) && (trim($item_lbt->dapan_hocvien)===trim($item->dap_an_4)))
                                                                                    <input type="radio" name="" id="" checked value="{{trim($item->dap_an_4)}}" disabled > <span class="text-danger">D. {!! $item->dap_an_4 !!}</span>
                                                                                @else
                                                                                    @if ((trim($item->dap_an_4)===trim($item->dap_an_dung)))
                                                                                        <input type="radio" name="" id="" checked value="{{trim($item->dap_an_4)}}" disabled > <span class="text-success">D. {!! $item->dap_an_4 !!}</span>
                                                                                    @else
                                                                                        <input type="radio" name="" id="" value="{{trim($item->dap_an_4)}}" disabled > D. {!! $item->dap_an_4 !!}
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="alert border-0 bg-light-success alert-dismissible fade show py-2">
                                                                        <strong>Đáp án đúng:</strong> {!! $item->dap_an_dung !!}
                                                                    </div>
                                                                </div>
                                                                @endif
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="">
                                        <div class="card">
                                            <div class="card-body">
                                                
                                                <p class="my-0"><strong>Thời gian học viên làm bài: </strong>
                                                    @php
                                                        $start = Carbon\Carbon::parse($ketqua->created_at);
                                                        $end = Carbon\Carbon::parse($ketqua->updated_at);
                                                        $minutes = $end->diffInMinutes($start);
                                                        $seconds = $end->diffInSeconds($start);
                                                    @endphp
                                                    {{$minutes.' phút '.$seconds.' giây'}}
                                                </p>
                                                <p class="my-0"><strong>Thời gian bắt đầu: </strong>{{$ketqua->created_at->format('d-m-Y G:i:s')}}</p>
                                                <p class="my-0"><strong>Thời gian kết thúc: </strong>{{$ketqua->updated_at->format('d-m-Y G:i:s')}}</p>
                                                <p class="my-0"><strong>Số lượng câu hỏi: </strong>{{$baitap->soluong_cauhoi}} câu</p>
                                                <p class="my-0"><strong>Số câu đúng: </strong>{{$ketqua->soluong_caudung}}/{{$baitap->soluong_cauhoi}}</p>
                                                <p class="my-0"><strong>Điểm: </strong>{{$ketqua->tong_diem}}</p>
                                                {{-- <p class="my-0"><strong>Số lần làm bài: </strong>{{$solanlambai}}</p> --}}
                                                
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="mb-2">
                                                        <span  id="timer" name="time"></span>
                                                    </div>
                                                    @php
                                                        $demds =0;
                                                    @endphp
                                                    @foreach ($cauhoi as $itemch)
                                                    <?php $demds = $demds +1; ?>
                                                        @foreach ($danhsach as $item_ds)
                                                            @if ($itemch->id === $item_ds->id_cauhoi)
                                                                <div class="col-2 mt-2">
                                                                    <a href="#quest-{{$item_ds->id_cauhoi}}" class="menu-link btn {{trim($itemch->dap_an_dung) === trim($item_ds->dapan_hocvien) ? 'btn-success' : 'btn-danger'}} d-flex justify-content-center">{{$demds}}</a>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</main>
@endsection
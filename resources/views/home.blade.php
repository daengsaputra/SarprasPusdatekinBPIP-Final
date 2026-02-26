@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<main class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-xxl-6">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card overflow-hidden avtivity-card">
                            <div class="card-body">
                                <div class="d-flex gap-md-4 gap-3 align-items-center">
                                    <span class="avatar avatar-lg avatar-success rounded-circle border-0">
                                        <img src="{{ asset('evanto/assets/images/card/1.avif') }}" alt="progress">
                                    </span>
                                    <div>
                                        <p class="fs-14 mb-2">Weekly Progress</p>
                                        <span class="title text-black fs-28 fw-semibold">42%</span>
                                    </div>
                                </div>
                                <div class="progress position-absolute bottom-0 start-0 w-100" style="height:5px;">
                                    <div class="progress-bar bg-success rounded" style="width: 42%; height:5px;"></div>
                                </div>
                            </div>
                            <div class="effect bg-success"></div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="card overflow-hidden avtivity-card">
                            <div class="card-body">
                                <div class="d-flex gap-md-4 gap-3 align-items-center">
                                    <span class="avatar avatar-lg avatar-secondary rounded-circle border-0">
                                        <img src="{{ asset('evanto/assets/images/card/2.avif') }}" alt="running">
                                    </span>
                                    <div>
                                        <p class="fs-14 mb-2">Weekly Running</p>
                                        <span class="title text-black fs-28 fw-semibold">42km</span>
                                    </div>
                                </div>
                                <div class="progress position-absolute bottom-0 start-0 w-100" style="height:5px;">
                                    <div class="progress-bar bg-secondary rounded" style="width: 82%; height:5px;"></div>
                                </div>
                            </div>
                            <div class="effect bg-secondary"></div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="card overflow-hidden avtivity-card">
                            <div class="card-body">
                                <div class="d-flex gap-md-4 gap-3 align-items-center">
                                    <span class="avatar avatar-lg avatar-danger rounded-circle border-0">
                                        <img src="{{ asset('evanto/assets/images/card/3.avif') }}" alt="cycling">
                                    </span>
                                    <div>
                                        <p class="fs-14 mb-2">Daily Cycling</p>
                                        <span class="title text-black fs-28 fw-semibold">230 Km</span>
                                    </div>
                                </div>
                                <div class="progress position-absolute bottom-0 start-0 w-100" style="height:5px;">
                                    <div class="progress-bar bg-danger rounded" style="width: 90%; height:5px;"></div>
                                </div>
                            </div>
                            <div class="effect bg-danger"></div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="card overflow-hidden avtivity-card">
                            <div class="card-body">
                                <div class="d-flex gap-md-4 gap-3 align-items-center">
                                    <span class="avatar avatar-lg avatar-warning rounded-circle border-0">
                                        <img src="{{ asset('evanto/assets/images/card/4.webp') }}" alt="yoga">
                                    </span>
                                    <div>
                                        <p class="fs-14 mb-2">Morning Yoga</p>
                                        <span class="title text-black fs-28 fw-semibold">18:34:21</span>
                                    </div>
                                </div>
                                <div class="progress position-absolute bottom-0 start-0 w-100" style="height:5px;">
                                    <div class="progress-bar bg-warning rounded" style="width: 42%; height:5px;"></div>
                                </div>
                            </div>
                            <div class="effect bg-warning"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12 col-xxl-6">
                <div class="card">
                    <div class="card-header d-sm-flex justify-content-sm-between d-block pb-0 border-0">
                        <div class="pb-3 pb-sm-0">
                            <h4 class="card-title">Plan List</h4>
                            <p class="fs-13 mb-0">Lorem ipsum dolor sit amet, consectetur</p>
                        </div>
                        <div class="dropdown mb-3">
                            <button type="button" class="btn rounded btn-primary light" data-bs-toggle="dropdown">Running</button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="javascript:void(0)">Running</a>
                                <a class="dropdown-item" href="javascript:void(0)">Cycling</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0 pb-0">
                        <div id="chartBar"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-xxl-9">
                <div class="card">
                    <div class="card-header d-sm-flex d-block pb-0 border-0">
                        <div class="me-auto pe-3">
                            <h4 class="card-title">Recomended Trainer for You</h4>
                            <p class="fs-13 mb-0">Lorem ipsum dolor sit amet, consectetur</p>
                        </div>
                        <div class="clearfix">
                            <a href="{{ route('assets.loanable') }}" class="btn btn-primary rounded d-none d-md-block">View More</a>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="testimonial-one owl-carousel">
                            @foreach ([7,5,3,1] as $avatar)
                                <div class="text-center">
                                    <div class="my-4">
                                        <div class="avatar avatar-xl rounded-circle mb-2">
                                            <img src="{{ asset('evanto/assets/images/avatar/large/avatar'.$avatar.'.webp') }}" alt="trainer">
                                        </div>
                                        <h5 class="fs-16 mb-1"><a href="javascript:void(0)" class="text-black">Trainer {{ $avatar }}</a></h5>
                                        <p class="fs-14">Body Building Trainer</p>
                                        <div class="d-flex gap-2 align-items-center justify-content-center">
                                            <i class="fa fa-star text-warning"></i>
                                            <span class="fs-14 d-block pe-2 border-end text-black">4.4</span>
                                            <a href="javascript:void(0)" class="btn-link fs-14">Send Request</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-sm-flex justify-content-sm-between d-block pb-0 border-0">
                        <div class="pb-3 pb-sm-0">
                            <h4 class="card-title">Calories Chart</h4>
                            <p class="fs-13 mb-0">Lorem ipsum dolor sit amet, consectetur</p>
                        </div>
                        <div class="clearfix">
                            <select class="selectpicker form-select">
                                <option>Monthly</option>
                                <option>Weekly</option>
                                <option>Daily</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body px-3 pb-0">
                        <div id="chartTimeline"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-xxl-3">
                <div class="card featuredMenu">
                    <div class="card-header border-0">
                        <h4 class="card-title">Featured Diet Menu</h4>
                    </div>
                    <div class="card-body loadmore-content height700 dz-scroll pt-0" id="FeaturedMenusContent">
                        <div class="d-flex mb-4">
                            <img src="{{ asset('evanto/assets/images/menus/1.avif') }}" alt="menus" class="avatar avatar-lg rounded me-3">
                            <div>
                                <h5><a href="javascript:void(0)" class="text-black fs-16">Chinese Orange Fruit With Avocado Salad</a></h5>
                                <span class="fs-14 text-primary">Kevin Ignis</span>
                            </div>
                        </div>
                        <ul class="d-flex flex-wrap pb-2 border-bottom mb-3 justify-content-between">
                            <li class="me-3 mb-2"><i class="las la-clock scale5 me-2"></i><span class="fs-14 text-black">8-9 mins</span></li>
                            <li class="mb-2"><i class="fa-regular fa-star me-2 scale1 text-warning"></i><span class="fs-14 text-black">189 Reviews</span></li>
                        </ul>
                        <div class="d-flex mb-4">
                            <img src="{{ asset('evanto/assets/images/menus/2.avif') }}" alt="menus" class="avatar avatar-lg rounded me-3">
                            <div>
                                <h5><a href="javascript:void(0)" class="text-black fs-16">Fresh or Frozen (No Sugar Added) Fruits</a></h5>
                                <span class="fs-14 text-primary">Olivia Johanson</span>
                            </div>
                        </div>
                        <ul class="d-flex flex-wrap pb-2 border-bottom mb-3 justify-content-between">
                            <li class="me-3 mb-2"><i class="las la-clock scale5 me-2"></i><span class="fs-14 text-black">4-6 mins</span></li>
                            <li class="mb-2"><i class="fa-regular fa-star me-2 scale1 text-warning"></i><span class="fs-14 text-black">156 Reviews</span></li>
                        </ul>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <div class="d-grid gap-2">
                            <a href="{{ route('assets.index') }}" class="btn btn-outline-primary btn-sm">Data Aset</a>
                            <a href="{{ route('assets.import.form') }}" class="btn btn-outline-success btn-sm">Import Aset</a>
                            <a href="{{ route('assets.export') }}" class="btn btn-outline-info btn-sm">Export Aset</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('script')
<script src="{{ asset('evanto/assets/js/dashboard/dashboard-1.js') }}"></script>
@endpush

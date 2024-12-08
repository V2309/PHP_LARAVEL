@extends('layouts.layout')
@section('title', 'Blog - GoFood')
@section('content')

    <div class="app__container">
        <div class="banner-blog">
            <div class="stick-blog">
                <h1>
                    Fully-Prepared & Delivered Fresh To Daily
                </h1>
                <span>
                    Chúng tôi xin được cung cấp đến quý khách hàng một số bài viết có liên quan. Thông tin những bài viết là hoàn toàn đúng sự thật và
                    được đội ngũ nhân viên kiểm duyệt rõ ràng.
                </span>
            </div>
    
        </div>
        <div class="blog grid" style="margin-top:50px;">
            <h3 class="tittle-blog">
                Tổng hợp tất cả bài viết của GoFood.
            </h3>
            <div class="grid__row">
              
                
                    <div class="col-sm-4" style="padding-right:10px;padding-left:10px;">
                        <div class="picture-blog">
                            <a href="#" class="img-blog">
                                <img src="{{asset('frontend/images/bl1_1.png')}}" loading="lazy" />
                              
    
                            </a>
                            <div class="date-blog">
                                <span class="day">
                                    25
                                </span>
                                <span class="month">
                                    Dec
                                </span>
                                <span class="year">
                                    24
                                </span>
                            </div>
                        </div>
                        <div class="info-blog">
                            <a href="#" class="name-blog">
                                Ngày xưa trong mắt anh toàn em, bây giờ mắt anh chỉ toàn ngày xưa.
                            </a>
                            <p>
                                Có một loại tình yêu ngoài mặt nói là không muốn liên hệ, nhưng lại nhung nhớ đến cháy lòng, tìm thì sợ làm phiền, không tìm thì đau lòng đến mất ngủ.
                                Nếu từ bỏ một người dễ dàng đến vậy, thì ai lại tự dày vò bản thân mình nhiều đến như vậy.
                            </p>
                        </div>
                    </div>
    
                    <div class="col-sm-4" style="padding-right:10px;padding-left:10px;">
                        <div class="picture-blog">
                            <a href="#" class="img-blog">
                                <img src="{{asset('frontend/images/bl1_1.png')}}" loading="lazy" />
                              
    
                            </a>
                            <div class="date-blog">
                                <span class="day">
                                    25
                                </span>
                                <span class="month">
                                    Dec
                                </span>
                                <span class="year">
                                    24
                                </span>
                            </div>
                        </div>
                        <div class="info-blog">
                            <a href="#" class="name-blog">
                                Ngày xưa trong mắt anh toàn em, bây giờ mắt anh chỉ toàn ngày xưa.
                            </a>
                            <p>
                                Có một loại tình yêu ngoài mặt nói là không muốn liên hệ, nhưng lại nhung nhớ đến cháy lòng, tìm thì sợ làm phiền, không tìm thì đau lòng đến mất ngủ.
                                Nếu từ bỏ một người dễ dàng đến vậy, thì ai lại tự dày vò bản thân mình nhiều đến như vậy.
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-4" style="padding-right:10px;padding-left:10px;">
                        <div class="picture-blog">
                            <a href="#" class="img-blog">
                                <img src="{{asset('frontend/images/bl2_1.png')}}" loading="lazy" />
                              
    
                            </a>
                            <div class="date-blog">
                                <span class="day">
                                    25
                                </span>
                                <span class="month">
                                    Dec
                                </span>
                                <span class="year">
                                    24
                                </span>
                            </div>
                        </div>
                        <div class="info-blog">
                            <a href="#" class="name-blog">
                                Ngày xưa trong mắt anh toàn em, bây giờ mắt anh chỉ toàn ngày xưa.
                            </a>
                            <p>
                                Có một loại tình yêu ngoài mặt nói là không muốn liên hệ, nhưng lại nhung nhớ đến cháy lòng, tìm thì sợ làm phiền, không tìm thì đau lòng đến mất ngủ.
                                Nếu từ bỏ một người dễ dàng đến vậy, thì ai lại tự dày vò bản thân mình nhiều đến như vậy.
                            </p>
                        </div>
                    </div>
              
            </div>
        </div>
    
    </div>

    @endsection
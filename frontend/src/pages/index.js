"use client"
import FrontendLayouts from '@/components/Layouts/Frontend/FrontendLayouts'
import Head from 'next/head'
import Image from 'next/image'
import Carousel from "@/components/Layouts/Frontend/Slider/Carousel";
// import Flash_Deal_Product from "@/components/Layouts/Frontend/Flash_Deal_Product";
import CategoryWise from "@/components/Layouts/Frontend/Slider/CategoryWise";
import FoodSlider from "@/components/Layouts/Frontend/Slider/FoodSlider";
import BestSelling from "@/components/Layouts/Frontend/Slider/BestSelling";
import Blog from "@/components/Layouts/Frontend/Slider/Blog";
import Meta from "@/components/Meta";
import { ToastContainer, toast } from 'react-toastify';
import { useEffect, useState } from 'react'
import Link from 'next/link';
import Slider from 'react-slick'
import SingleProduct from "@/components/SingleProduct";
import axios from 'axios';
import { data } from 'jquery';


const Home = ({popularCategories, all_slider, ad1, ad2}) => {


    const settings = {
        dots: true,
        infinite: true,
        speed: 500,
        slidesToShow: 3,
        slidesToScroll: 1,
        initialSlide: 3,
        autoplay: true,
        pauseOnHover: true,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
              infinite: true,
              dots: true
            }
          },
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
              initialSlide: 2
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
      }



    const [flashsaleproduct, setflashsaleproduct] = useState([]);
    const [product_onsale, setproduct_onsale] = useState([]);
    const [product_bestselling, setproduct_bestselling] = useState([]);
    

    const [Fash, setFash] = useState(0);
    const flashsaleproductFunction = async () => {
    try {
        const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/flash-sale`);
        const data = await response.json();
        setflashsaleproduct(data);
        if(data.status == 1){
            setFash(1);
            setTimeout(function() {
                function getTimeRemaining(endtime) {
                    var t = Date.parse(endtime) - Date.parse(new Date());
                    /***** CONVERT THE TIME TO A USEABLE FORMAT *****/
                    var seconds = Math.floor((t / 1000) % 60);
                    var minutes = Math.floor((t / 1000 / 60) % 60);
                    var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
                    var days = Math.floor(t / (1000 * 60 * 60 * 24));
            
                    if(seconds > 0){
                        setFash(1);
                    }else{
                        setFash(0);
                    }

                    /***** OUTPUT THE CLOCK DATA AS A REUSABLE OBJECT *****/
                    return {
                        'total': t,
                        'days': days,
                        'hours': hours,
                        'minutes': minutes,
                        'seconds': seconds
                    };
                }
            
                /***** DISPLAY THE CLOCK AND STOP IT WHEN IT REACHES ZERO *****/
                if(new Date(Date.parse(data.expired_date)) > new Date(Date.parse(new Date()))){

                    /***** SET A VALID END DATE *****/
                    var deadline = new Date(Date.parse(data.expired_date));


                    initializeClock('clockdiv-1', deadline);
                        function initializeClock(id, endtime) {
                            var clock = document.getElementById(id);
                            var daysSpan = clock.querySelector('.days');
                            var hoursSpan = clock.querySelector('.hours');
                            var minutesSpan = clock.querySelector('.minutes');
                            var secondsSpan = clock.querySelector('.seconds');
                            function updateClock() {
                                var t = getTimeRemaining(endtime);
                                daysSpan.innerHTML = t.days;
                                hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
                                minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
                                secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);
                    
                                //.log('total====', t.total);

                                // if (t.total <= 0) {
                                //     clearInterval(timeinterval);
                                // }
                            }
                            updateClock(); // run function once at first to avoid delay
                            var timeinterval = setInterval(updateClock, 1000);
                        }
                    
                }else{
                    setFash(0);
                }
            },3000);
        }
        
    } catch (error) {

    }
    }

    const load_onsale_product = async () => {
        try {
            const response = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/sale?upazila_id=`+localStorage.getItem('upazail_id'));
            setproduct_onsale(response.data);
        } catch (error) {
            console.log('setproduct_onsale error=', error);
        }
    }
    const load_bestselling_product = async () => {
        try {
            const response = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/best-sellers?upazila_id=`+localStorage.getItem('upazail_id'));
            setproduct_bestselling(response.data);
        } catch (error) {
            console.log('setproduct_bestselling error=', error);
        }
    }





    const [promotional_banner_1, setpromotional_banner_1] = useState([]);
    const [promotional_banner_2, setpromotional_banner_2] = useState([]);
    const [promotional_banner_3, setpromotional_banner_3] = useState([]);
    const [promotional_banner_4, setpromotional_banner_4] = useState([]);
    const [promotional_banner_5, setpromotional_banner_5] = useState([]);
    const [promotional_banner_6, setpromotional_banner_6] = useState([]);
    
    const load_promotional_banner = async () => {
      try {
          const response =await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-promotional-banner`);
          setpromotional_banner_1(response.data.promotional_banner_1);
          setpromotional_banner_2(response.data.promotional_banner_2);
          setpromotional_banner_3(response.data.promotional_banner_3);
          setpromotional_banner_4(response.data.promotional_banner_4);
          setpromotional_banner_5(response.data.promotional_banner_5);
          setpromotional_banner_6(response.data.promotional_banner_6);
      }catch (error) {
    
      }
    }






    useEffect(() => {
        flashsaleproductFunction();
        load_onsale_product();
        load_promotional_banner();
        load_bestselling_product();
    }, []);






    return (
        <FrontendLayouts>
            <Meta title="Home | Dhroobo" keywords="dhroobo keywords" decription="dhroobo decription" />
            <div className="fullpage">
                <section className="home-section pt-2">
                <div className="container-fluid-lg">
                    <div className="row g-4">
                        <div className="col-xl-8 ratio_65">
                            <Carousel all_slider={all_slider} />
                        </div>

                        <div className="col-xl-4 ratio_65">
                            <div className="row g-4">
                                {ad1?.status == 1 ? (
                                <div className="col-xl-12 col-md-6">
                                    <div className="home-contain vh30">
                                        <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${ad1.banner}`} className="bg-img blur-up lazyload" layout='fill' objectFit='cover'  alt="" />
                                        <div className="home-detail p-center-left home-p-sm w-75">
                                            <div>
                                                {/* <h2 className="mt-0 text-danger">45% <span className="discount text-title">OFF</span>
                                                </h2> */}
                                                {/* <h3 className="theme-color">Nut Collection</h3>
                                                <p className="w-75">We deliver organic vegetables & fruits</p> */}
                                                {ad1.link_type == 'internal_url' ? (
                                                    <Link href={`${ad1.link}`} className="shop-button">Shop Now <i
                                                            className="fa-solid fa-right-long"></i></Link>
                                                ):(
                                                    <a target='_black' href={`${ad1.link}`} className="shop-button">Shop Now <i
                                                        className="fa-solid fa-right-long"></i></a>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                ):('')}

                                {ad2?.status == 1 ? (
                                <div className="col-xl-12 col-md-6">
                                    <div className="home-contain vh30">
                                        <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${ad2.banner}`}className="bg-img blur-up lazyload" layout='fill' objectFit='cover'  alt="" />
                                        <div className="home-detail p-center-left home-p-sm w-75">
                                            <div className='abButton'>
                                                {/* <h2 className="mt-0 text-danger">45% <span className="discount text-title">OFF</span>
                                                </h2> */}
                                                {/* <h3 className="theme-color">Nut Collection</h3>
                                                <p className="w-75">We deliver organic vegetables & fruits</p> */}
                                                {ad2.link_type == 'internal_url' ? (
                                                    <Link href={`${ad2.link}`} className="shop-button">Shop Now <i
                                                            className="fa-solid fa-right-long"></i></Link>
                                                ):(
                                                    <a target='_black' href={`${ad2.link}`} className="shop-button">Shop Now <i
                                                        className="fa-solid fa-right-long"></i></a>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                ):('')}


                            </div>
                        </div>
                    </div>
                </div>
                </section>





                {/* <section className="banner-section ratio_60">
                <div className='container-fluid-lg ratio_60'>
                    <Carousel />
                </div>
                </section> */}



                <section className="product-section">
                    <div className="container-fluid-lg">
                        <div className="row g-sm-4 g-3">
                            <div className="col-xxl-3 col-xl-4 d-none d-xl-block">
                                <div className="p-sticky">
                                    <div className="category-menu">
                                        <h3>Popular Category</h3>
                                        <ul>
                                            
                                            {popularCategories?.map((category1, index1) => (
                                            <li key={index1}>
                                                <div className="category-list">
                                                <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${category1.image}`} width={100}  height={100}  alt="" />
                                                    <h5>
                                                        <Link href={`/category/${category1.slug}`} >{category1.title} </Link>
                                                    </h5>
                                                </div>
                                            </li>
                                            ))}
                                        </ul>
                                    </div>
                                    {promotional_banner_1.status == 1 ? (
                                    <div className="ratio_156 section-t-space">
                                        <div className="home-contain hover-effect">
                                            <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${promotional_banner_1.banner}`} className="bg-img blur-up lazyload"
                                                width={375} height={586} alt=""/>
                                            <div className="home-detail p-top-left home-p-medium">
                                                <div>
                                                    {/* <h6 className="text-yellow home-banner">Seafood</h6>
                                                    <h3 className="text-uppercase fw-normal"><span
                                                            className="theme-color fw-bold">Freshes</span> Products</h3>
                                                    <h3 className="fw-light">every hour</h3> */}
                                                        {promotional_banner_1.link_type == 'internal_url' ? (
                                                            <Link href={`${promotional_banner_1.link}`} className="shop-button">
                                                                <button  className="btn btn-animation text-light btn-md mend-auto">Shop Now <i className="fa-solid fa-arrow-right icon"></i></button>
                                                            </Link>
                                                        ):(
                                                            <a target='_black' href={`${promotional_banner_1.link}`} className="shop-button"> <button  className="btn btn-animation text-light btn-md mend-auto">Shop Now <i className="fa-solid fa-arrow-right icon"></i></button></a>
                                                        )}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    ):('')}

                                    {/* <div className="ratio_medium section-t-space">
                                        <div className="home-contain hover-effect">
                                            <Image src="/assets/images/vegetable/banner/11.jpg" className="img-fluid blur-up lazyload"
                                                width={375} height={586} alt=""/>
                                            <div className="home-detail p-top-left home-p-medium">
                                                <div>
                                                    <h4 className="text-yellow text-exo home-banner">Organic</h4>
                                                    <h2 className="text-uppercase fw-normal mb-0 text-russo theme-color">fresh</h2>
                                                    <h2 className="text-uppercase fw-normal text-title">Vegetables</h2>
                                                    <p className="mb-3">Super Offer to 50% Off</p>
                                                    <button 
                                                        className="btn btn-animation text-light btn-md mend-auto">Shop Now <i
                                                            className="fa-solid fa-arrow-right icon"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div> */}

                                    <div className="section-t-space mb-3">
                                        <div className="category-menu">
                                        <div className='dangerouslySetInnerHTML' dangerouslySetInnerHTML={{ __html: product_bestselling?.title }} />

                                            <ul className="product-list border-0 p-0 d-block">
                                            {product_bestselling?.items?.map((data, index) => (
                                                <li>
                                                    <div className="offer-product">
                                                        <Link href={'/product/'+`${data?.slug}`} className="offer-image">
                                                            <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${data.default_image}`}
                                                                className="blur-up lazyload"  width={180} height={140} alt=""/>
                                                        </Link>

                                                        <div className="offer-detail">
                                                            <div>
                                                                <Link href={'/product/'+`${data?.slug}`} className="text-title">
                                                                    <h6 className="name">{ data.title }</h6>
                                                                </Link>
                                                                <h6 className="price theme-color"> BDT { data.price_after_offer }</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                ))}



                                            </ul>
                                        </div>
                                    </div>

                                    {/* <div className="section-t-space">
                                        <div className="category-menu">
                                            <h3>Customer Comment</h3>

                                            <div className="review-box">
                                                <div className="review-contain">
                                                    <h5 className="w-75">We Care About Our Customer Experience</h5>
                                                    <p>In publishing and graphic design, Lorem ipsum is a placeholder text commonly
                                                        used to demonstrate the visual form of a document or a typeface without
                                                        relying on meaningful content.</p>
                                                </div>

                                                <div className="review-profile">
                                                    <div className="review-image">
                                                        <Image src="/assets/images/vegetable/review/1.jpg"
                                                            className="img-fluid blur-up lazyload"  width={180} height={140} alt=""/>
                                                    </div>
                                                    <div className="review-detail">
                                                        <h5>Tina Mcdonnale</h5>
                                                        <h6>Sale Manager</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> */}
                                </div>
                            </div>

                            <div className="col-xxl-9 col-xl-8">
                                {Fash  > 0 ? (
                                    <> 
                                <div className="title title-flex">
                                
                                    <div dangerouslySetInnerHTML={{ __html: flashsaleproduct?.title }} />            


                                    <div className="timing-box mb-3">
                                        <div className="timing">
                                            <i data-feather="clock"></i>
                                            <h6 className="name">Expires in :</h6>
                                            <div className="time" id="clockdiv-1" data-hours="1" data-minutes="2" data-seconds="3">
                                                <ul>
                                                    <li>
                                                        <div className="counter">
                                                            <div className="days">
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div className="counter">
                                                            <div className="hours">
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div className="counter">
                                                            <div className="minutes">
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div className="counter">
                                                            <div className="seconds">
                                                                <h6></h6>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div className="section-b-space">
                                    <div className="product-border border-row overflow-hidden">
                                        <div className="product-box-slider no-arrow">
                                        <Slider {...settings}>
                                            {flashsaleproduct?.items?.map((chunckdata, index) => (
                                            <div key={index}>
                                        
                                            <div className="row m-0">
                                                {chunckdata ? (
                                                    <>
                                                    {chunckdata.map((data, i) => (
                                                        
                                                        <div key={i} className="col-12 px-0">
                                                        <SingleProduct data={data}> </SingleProduct>

                                                        </div>
                                                    ))}
                                                    </>
                                                ):('')}
                                            </div>
                                            </div>
                                            ))}
                                        </Slider>
                                        </div>
                                    </div>
                                </div>
                                </>
                                ):('')}







{promotional_banner_2.status == 1 ? (
                                <div className="section-t-space section-b-space">
                                    <div className="row g-md-4 g-3">
                                        <div className="col-xxl-8 col-xl-12 col-md-7">
                                            <div className="banner-contain hover-effect relative_div2">
                                                <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${promotional_banner_2.banner}`} className="bg-img blur-up lazyload"
                                                    layout='fill' objectFit='cover' alt=""/>
                                                <div className="banner-details p-center-left p-4 absulate_div2">
                                                    <div>
                                                        {/* <h2 className="text-kaushan fw-normal theme-color">Get Ready To</h2>
                                                        <h3 className="mt-2 mb-3">TAKE ON THE DAY!</h3>
                                                        <p className="text-content banner-text">In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate.</p>
                                                        */}
                                                        <div>
                                                            
                                                        {promotional_banner_2.link_type == 'internal_url' ? (
                                                            <Link href={`${promotional_banner_2.link}`}>
                                                                <button className="btn btn-animation text-light btn-sm mend-auto">Shop Now <i className="fa-solid fa-arrow-right icon"></i></button>

                                                            </Link>
                                                        ):(
                                                            <a target='_black' href={`${promotional_banner_2.link}`}>
                                                            <button className="btn btn-animation text-light btn-sm mend-auto">Shop Now <i className="fa-solid fa-arrow-right icon"></i></button>
                                                            
                                                            </a>
                                                        )}


                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                ):('')}

{/* 
                                <div className="title">
                                    <h2>Bowse by Categories</h2>
                                </div>

                                <div className="category-slider-2 product-wrapper no-arrow">
                                    <CategoryWise/>
                                </div> */}

                                {/* <div className="section-t-space section-b-space">
                                    <div className="row g-md-4 g-3">
                                        <div className="col-md-6">
                                            <div className="banner-contain hover-effect">
                                                <Image src="/assets/images/vegetable/banner/9.jpg" className="bg-img blur-up lazyload" width={583} height={157}
                                                    alt="" />
                                                <div className="banner-details p-center-left p-4">
                                                    <div>
                                                        <h3 className="text-exo">50% offer</h3>
                                                        <h4 className="text-russo fw-normal theme-color mb-2">Testy Mushrooms</h4>
                                                        <button 
                                                            className="btn btn-animation text-light btn-sm mend-auto">Shop Now <i
                                                                className="fa-solid fa-arrow-right icon"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="col-md-6">
                                            <div className="banner-contain hover-effect">
                                                <Image src="/assets/images/vegetable/banner/10.jpg" className="bg-img blur-up lazyload"
                                                     width={583} height={157}  alt="" />
                                                <div className="banner-details p-center-left p-4">
                                                    <div>
                                                        <h3 className="text-exo">50% offer</h3>
                                                        <h4 className="text-russo fw-normal theme-color mb-2">Fresh MEAT</h4>
                                                        <button 
                                                            className="btn btn-animation text-light btn-sm mend-auto">Shop Now <i
                                                                className="fa-solid fa-arrow-right icon"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> */}


                                <div className="title d-block">
                                    <div dangerouslySetInnerHTML={{ __html: product_onsale?.title }} />
                                    
                                    
                                </div>

                                <div className="product-border overflow-hidden">
                                    <div className="product-box-slider no-arrow">
                                        <FoodSlider items={product_onsale.items} />
                                    </div>
                                </div>

                                




                            

                            
                                
                                
                                    <div className="title section-t-space">
                                        <h2>Featured Blog</h2>
                                    </div>

                                    <div className="slider-3-blog ratio_65 no-arrow product-wrapper">
                                        <Blog />
                                    </div>
                            </div>
                        </div>
                    </div>
                </section>
{/*             
                <section className="newsletter-section section-b-space">
                    <div className="container-fluid-lg">
                        <div className="newsletter-box newsletter-box-2">
                            <div className="newsletter-contain py-5">
                                <div className="container-fluid">
                                    <div className="row">
                                        <div className="col-xxl-4 col-lg-5 col-md-7 col-sm-9 offset-xxl-2 offset-md-1">
                                            <div className="newsletter-detail">
                                                <h2>Join our newsletter and get...</h2>
                                                <h5>$20 discount for your first order</h5>
                                                <div className="input-box">
                                                    <input type="email" className="form-control" id="exampleFormControlInput1"
                                                        placeholder="Enter Your Email" />
                                                    <i className="fa-solid fa-envelope arrow"></i>
                                                    <button className="sub-btn  btn-animation">
                                                        <span className="d-sm-block d-none">Subscribe</span>
                                                        <i className="fa-solid fa-arrow-right icon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section> */}



            </div>
        </FrontendLayouts>
    )
}

export default Home

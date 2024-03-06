
import Head from 'next/head'
import { useEffect, useState } from 'react'
import Image from 'next/image'
import VerticalSlider from "@/components/Layouts/Frontend/Slider/VerticalSlider";
// import LogoutButton from "@/components/Buttons/LogoutButton";
import { useSelector, useDispatch } from 'react-redux';
import axios from 'axios';
import api from "@/components/Layouts/Frontend/api";
import Link from 'next/link';
import { getSession, signIn, signOut, useSession,  } from 'next-auth/react';
import RemoveCartBtn from "@/components/Buttons/RemoveCartBtn";
import Input from '@/components/Input'
import { selectTotalQuantity, InitCart } from  '../../../lib/features/cart/cartSlice';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';


const Header =({information, categories}) => {
    const { data:userdata, status, session }= useSession();
    const dispatch = useDispatch();
    const cart = useSelector((state) => state.cart);
    //console.log('CHECK CART FROM HEADER = ', cart);
    const totalQuantity = useSelector(selectTotalQuantity);

  
  const [navbars, setNavbars] = useState([]);
  const navbar = async () => {
    try {
        const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/navigation-bars`);
        const data = await response.json();
        setNavbars(data);
    } catch (error) {

    }
  }
  const handleLogout = async () => {
    await signOut({redirect: false, callbackUrl: "/login"});
  }
  





const handleChange2 = (e) => {
    const { name, value } = e.target;
    setloginData((prevData) => ({ ...prevData, [name]: value }));
}
const [loginData2, setloginData] = useState({
    phone: '',
    password: '',
});
const popupLoginSubmit = async (e) => {
    e.preventDefault();
    const phone = loginData2.phone;
    const password = loginData2.password;
    const result = await signIn('credentials', {
        phone,
        password,
        redirect: false,
    });
  
    if(result.error) {
        $('.other_error').text('Error ! Invalid credentials.');
      } else {
        await dispatch(InitCart());
        //console.log('session=', session);

        $('.btn-close').trigger('click');
    }
}

const [showAddress, setShowAddress] = useState({
    selected_division:null,
    selected_district:null,
    selected_upazila:null,
    selected_union:null,

    divisions:[],
    districts:[],
    upazilas:[],
    unions:[],
})


const  handleshowAddress = async (e) => {
    const { name, value } = e.target;
    const token = localStorage.getItem('token');
    if(name == 'shipping_division'){
        const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-district/`+value,{
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        const data = await response.json();
        if(data){setShowAddress(prevData => ({ ...prevData, districts:data, selected_division:value, upazilas:[], unions:[]})); }
    }else if(name == 'shipping_district'){
        const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-upazila/`+value,{
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        const data = await response.json();
        if(data){setShowAddress(prevData => ({ ...prevData, upazilas:data,selected_district:value, unions:[]})); }
    }else if(name == 'shipping_thana'){
        const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-union/`+value,{
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        const data = await response.json();
        console.log('Changing option selected_upazila=', value);
        if(data){setShowAddress(prevData => ({ ...prevData, unions:data, selected_upazila:value})); }
    }else if(name == 'shipping_union'){
        setShowAddress(prevData => ({ ...prevData, selected_union:value}));
    }else{
        setShowAddress((prevData) => ({ ...prevData, [name]: value }));
    }
}

const openlocationmodalbtn = async () => {
    try {
        const response_divisons = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-divisons`,{
            headers: {
                Authorization: `Bearer ${userdata?.accessToken}`,
            },
        });
        setShowAddress({
            divisions:response_divisons.data?.divisons,
        });
    } catch (error) {
        console.log('error ', error);
    }
}

const location_submit = async (e) => {
    console.log('selected_division=', showAddress.selected_division);


    if(!showAddress.selected_division || showAddress.selected_division == 'undefined' || showAddress.selected_division < 1){
        toast.error('Select division first.', {
            position: "bottom-right",
            autoClose: 2000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: false,
            progress: undefined,
            theme: "light",
        });
        return true;
     }else if(!showAddress.selected_district || showAddress.selected_district == 'undefined' || showAddress.selected_district < 1){
        toast.error('Select district first.', {
            position: "bottom-right",
            autoClose: 2000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: false,
            progress: undefined,
            theme: "light",
        });
        return true;
     }else if(!showAddress.selected_upazila || showAddress.selected_upazila == 'undefined' ||  showAddress.selected_upazila < 1){
        toast.error('Select upazila.', {
            position: "bottom-right",
            autoClose: 2000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: false,
            progress: undefined,
            theme: "light",
        });
        return true;
     }else{
        localStorage.setItem("upazila_id", showAddress.selected_upazila);
        window.location.reload();
        return true;
    }
}


useEffect(() =>{
    openlocationmodalbtn();
    navbar();
  },[]);


    return (
        
        <div className='fullheader'>
            <ToastContainer />
            

            {/* <Topbar/> */}
        <header className="pb-md-4 pb-0">
        {/* 
        <div className="header-top">
            <div className="container-fluid-lg">
                <div className="row">
                    <div className="col-xxl-3 d-xxl-block d-none">
                        <div className="top-left-header">
                            <i className="iconly-Location icli text-white"></i>
                            <span className="text-white">1418 Riverwood Drive, CA 96052, US</span>
                        </div>
                    </div>

                    <div className="col-xxl-6 col-lg-9 d-lg-block d-none">
                        <div className="header-offer">
                            <div className="notification-slider">
                            
                            <VerticalSlider/>

                            status==={status}
                            </div>
                        </div>
                    </div>

                    <div className="col-lg-3">
                        <ul className="about-list right-nav-about">
                            <li className="right-nav-list">
                                <div className="dropdown theme-form-select">
                                    <button className="btn dropdown-toggle" type="button" id="select-language"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <Image src="/assets/images/country/united-states.png"
                                            className="img-fluid blur-up lazyload" width={100}  height={100}  alt="" />
                                        <span>English  </span>
                                    </button>
                                    <ul className="dropdown-menu dropdown-menu-end" aria-labelledby="select-language">
                                        <li>
                                            <a className="dropdown-item" href="#" id="english">
                                                <Image src="/assets/images/country/united-kingdom.png"
                                                    className="img-fluid blur-up lazyload" width={100}  height={100}  alt="" />
                                                <span>English</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a className="dropdown-item" href="#" id="france">
                                                <Image src="/assets/images/country/bangladesh.png"
                                                    className="img-fluid blur-up lazyload" width={100}  height={100}  alt="" />
                                                <span>বাংলা</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        */}


        <div className="top-nav top-header sticky-header">
            <div className="container-fluid-lg">
                <div className="row">
                    <div className="col-12">
                        <div className="navbar-top">
                            <button className="navbar-toggler d-xl-none d-inline navbar-menu-button" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#primaryMenu">
                                <span className="navbar-toggler-icon">
                                    <i className="fa-solid fa-bars"></i>
                                </span>
                            </button>
                            
                            <Link href="/" className="web-logo nav-logo" >
                                <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${information.header_logo}`} className="img-fluid blur-up lazyload" width={100}  height={100}  alt="" />
                            </Link>

                            <div className="middle-box">
                                <div className="location-box">
                                    <button className="btn location-button" data-bs-toggle="modal"data-bs-target="#locationModal">
                                        <span className="location-arrow">
                                            <i data-feather="map-pin"></i>
                                        </span>
                                        {information?.upazila_title ? (
                                            <span>{information?.upazila_title.substring(0, 15)}</span>
                                        ):( <>
                                            <span className="locat-name">My Location</span>
                                            <i className="fa-solid fa-angle-down"></i>
                                            </>
                                        )}
                                    </button>
                                </div>

                                <div className="search-box">
                                    <div className="input-group">
                                        <input type="search" className="form-control" placeholder="I'm searching for..."
                                            aria-label="Recipient's username" aria-describedby="button-addon2"/>
                                        <button className="btn" type="button" id="button-addon2">
                                            <i data-feather="search"></i>
                                        </button>
                                    </div>
                                </div>



                            </div>

                            <div className="rightside-box">
                                <div className="search-full">
                                    <div className="input-group">
                                        <span className="input-group-text">
                                         <i data-feather="search" className="font-light"></i> 
                                        </span>
                                        <input type="text" className="form-control search-type" placeholder="Search here.."/>
                                        <span className="input-group-text close-search">
                                            <i data-feather="x" className="font-light"></i>
                                        </span>
                                    </div>
                                </div>
                                <ul className="right-side-menu">
                                    <li className="right-side">
                                        <div className="delivery-login-box">
                                            <div className="delivery-icon">
                                                <div className="search-box">
                                                    <i data-feather="search"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li className="right-side">
                                    {userdata ? (
                                        <Link href="/compare" className="btn p-0 position-relative header-wishlist">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="round" strokeLinejoin="round" className="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                            {userdata && userdata.compares > 0 ?  ( 
                                                <span className="position-absolute top-0 start-100 translate-middle badge">{userdata?.compares}</span>
                                            ):('')}
                                        </Link>
                                        ):(
                                            <a  data-bs-toggle="modal" data-bs-target="#popupLogin" className="btn p-0 position-relative header-wishlist"><i data-feather="refresh-cw"></i></a>
                                        )}
                                    </li>
                                    <li className="right-side header-badge">
                                        {userdata ? (
                                        <Link href="/wishlist" className="btn p-0 position-relative header-wishlist">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                            {userdata.wishlists > 0 ?  ( 
                                                <span className="position-absolute top-0 start-100 translate-middle badge">{userdata?.wishlists}</span>
                                            ):('')}
                                        </Link>
                                        ):(
                                            <a  data-bs-toggle="modal" data-bs-target="#popupLogin" className="btn p-0 position-relative header-wishlist"><i data-feather="heart"></i></a>
                                        )}
                                    </li>
                                    <li className="right-side">
                                        <div className="onhover-dropdown header-badge">
                                            <Link href="/cart" className="btn p-0 position-relative header-wishlist">
                                                <i data-feather="shopping-cart"></i>
                                                {totalQuantity > 0 ?  ( 
                                                <span className="position-absolute top-0 start-100 translate-middle badge">  {totalQuantity} </span>
                                                ):('')}
                                            </Link>

                                            <div className="onhover-div">
                                                <ul className="cart-list">
                                                {cart?.initcart?.cart?.map((cartgroup, index) => (
                                                    <span key={index}>
                                                        {cartgroup.items?.map((cart, index) => (
                                                            <span key={index}>
                                                                <li className="product-box-contain">
                                                                    <div className="drop-cart">
                                                                        <Link href={`/product/${cart.product.slug}`} className="drop-image">
                                                                            <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${cart.product.default_image}`} className="blur-up lazyload" width={100}  height={100}  alt="" />
                                                                        </Link>

                                                                        <div className="drop-contain">
                                                                            <Link href={`/product/${cart.product.slug}`}>
                                                                                <h5>{cart.product.title}</h5>
                                                                            </Link>
                                                                            <small><span>{cart?.qty} x</span> BDT { cart.price }</small>
                                                                            <button className="close-button close_button">
                                                                            
                                                                                <RemoveCartBtn  data={cart.row_id}></RemoveCartBtn>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </span>
                                                        ))}
                                                    </span>
                                                ))}
                                                </ul>

                                                <div className="price-box">
                                                    <h5>Sub Total :</h5>
                                                    <h4 className="theme-color fw-bold">BDT { cart?.initcart?.sub_total  }</h4>
                                                </div>

                                                <div className="button-group">
                                                    <Link href="/cart" className="btn btn-sm cart-button">View Cart</Link>
                                                    <Link href="/checkout" className="btn btn-sm cart-button theme-bg-color
                                                    text-white">Checkout</Link>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li className="right-side onhover-dropdown">
                                        <div className="delivery-login-box">
                                            <div className="delivery-icon">
                                                <i data-feather="user"></i>
                                            </div>
                                            <div className="delivery-detail">
                                                <h6>Hello,</h6>
                                                <h5>My Account</h5>
                                            </div>
                                        </div>

                                        <div className="onhover-div onhover-div-login">
                                            <ul className="user-box-name">
                                            {userdata ? ( 
                                                <>
                                                    <li className="product-box-contain">
                                                        <Link href="/my_account">My Account</Link>
                                                    </li>
                                                    <li className="product-box-contain">
                                                        <Link href="/my_account" onClick={handleLogout}>Logout</Link>
                                                    </li>
                                                </>
                                            ):(
                                                <>
                                                    <li className="product-box-contain">
                                                        <Link href="login">Log In</Link>
                                                    </li>

                                                    <li className="product-box-contain">
                                                        <Link href="/register">Register</Link>
                                                    </li>

                                                    <li className="product-box-contain">
                                                        <a href="forgot.html">Forgot Password</a>
                                                    </li>
                                                
                                                </>
                                            )}
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div className="container-fluid-lg">
            <div className="row">
                <div className="col-12">
                    <div className="header-nav">
                        <div className="header-nav-left">
                            <button className="dropdown-category">
                                <i data-feather="align-left"></i>
                                <span>All Categories</span>
                            </button>

                            <div className="category-dropdown">
                                <ul className="category-list">

                                    {categories?.map((category1, index1) => (
                                    <li key={index1} className="onhover-category-list">
                                        <Link href={`/category/${category1.slug}`} className="category-name">
                                            <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${category1.image}`} width={100}  height={100}  alt="" />
                                            <h6>{category1.title}</h6>
                                            <i className="fa-solid fa-angle-right"></i> 
                                        </Link>

                                        <div className="onhover-category-box w-100">
                                            {category1?.categories?.map((category2, index2) => (
                                            <div key={index2} className="list-1">
                                                <div className="category-title-box">
                                                <Link href={`/category/${category2.slug}`} className='secondlayer'> <h5>{category2.title}</h5> </Link>
                                                    {category2?.categories.length > 0 ? (
                                                        <i className="fa-solid fa-angle-right"></i>
                                                    ):('')}
                                                </div>

                                                {category2?.categories.length > 0 ? (
                                                <div className='thirdLayerparent'>
                                                <div className='thirdLayer'>
                                                    <ul>
                                                        {category2?.categories?.map((category3, index3) => (
                                                        <li key={index3}> <Link href={`/category/${category3.slug}`}>{category3.title}</Link></li>
                                                        ))}
                                                    </ul>
                                                </div>
                                                </div>
                                                ):('')}
                                            </div>
                                            ))}
                                        </div>
                                    </li>
                                    ))}

                                </ul>
                            </div>
                        </div>

                        <div className="header-nav-middle">
                            <div className="main-nav navbar navbar-expand-xl navbar-light navbar-sticky">
                                <div className="offcanvas offcanvas-collapse order-xl-2" id="primaryMenu">
                                    <div className="offcanvas-header navbar-shadow">
                                        <h5>Menu</h5>
                                        <button className="btn-close lead" type="button" data-bs-dismiss="offcanvas"
                                            aria-label="Close"></button>
                                    </div>
                                    <div className="offcanvas-body">
                                        <ul className="navbar-nav">
                                            {navbars.map((data, i) => (
                                                <li key={i} className="nav-item">
                                                    {data.link_type == "Internal" ? (

                                                    <>
                                                        {data.link == "about" ? (
                                                            <Link href={'/pages/'+data.link} className="nav-link">{data.title}</Link>

                                                        ):(
                                                            <Link href={'/'+data.link} className="nav-link">{data.title}</Link>

                                                        )}
                                                    </>

                                                    ):(
                                                        <a href={data.link} target='__blank' className="nav-link">{data.title}</a>
                                                    )}
                                                </li>
                                            ))}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="header-nav-right">
                            <button className="btn deal-button" data-bs-toggle="modal" data-bs-target="#deal-box">
                                <i data-feather="zap"></i> 
                                <span>Deal Today</span>
                            </button>
                        </div>

                        {/* deal-box Modal Start */}
                        <div className="modal fade theme-modal deal-modal" id="deal-box" tabIndex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div className="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
                                <div className="modal-content">
                                    <div className="modal-header">
                                        <div>
                                            <h5 className="modal-title w-100" id="deal_today">Deal Today</h5>
                                            <p className="mt-1 text-content">Recommended deals for you.</p>
                                        </div>
                                        <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            <i className="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                    <div className="modal-body">
                                        <div className="deal-offer-box">
                                            <ul className="deal-offer-list">
                                                <li className="list-1">
                                                    <div className="deal-offer-contain">
                                                        <a href="shop-left-sidebar.html" className="deal-image">
                                                            <Image src="/assets/images/vegetable/product/10.png" className="blur-up lazyload"
                                                                width={100}  height={100}  alt="" />
                                                        </a>

                                                        <a href="shop-left-sidebar.html" className="deal-contain">
                                                            <h5>Blended Instant Coffee 50 g Buy 1 Get 1 Free</h5>
                                                            <h6>$52.57 <del>57.62</del> <span>500 G</span></h6>
                                                        </a>
                                                    </div>
                                                </li>

                                                <li className="list-2">
                                                    <div className="deal-offer-contain">
                                                        <a href="shop-left-sidebar.html" className="deal-image">
                                                            <Image src="/assets/images/vegetable/product/11.png" className="blur-up lazyload"
                                                                width={100}  height={100}  alt="" />
                                                        </a>

                                                        <a href="shop-left-sidebar.html" className="deal-contain">
                                                            <h5>Blended Instant Coffee 50 g Buy 1 Get 1 Free</h5>
                                                            <h6>$52.57 <del>57.62</del> <span>500 G</span></h6>
                                                        </a>
                                                    </div>
                                                </li>

                                                <li className="list-3">
                                                    <div className="deal-offer-contain">
                                                        <a href="shop-left-sidebar.html" className="deal-image">
                                                            <Image src="/assets/images/vegetable/product/12.png" className="blur-up lazyload"
                                                                width={100}  height={100}  alt="" />
                                                        </a>

                                                        <a href="shop-left-sidebar.html" className="deal-contain">
                                                            <h5>Blended Instant Coffee 50 g Buy 1 Get 1 Free</h5>
                                                            <h6>$52.57 <del>57.62</del> <span>500 G</span></h6>
                                                        </a>
                                                    </div>
                                                </li>

                                                <li className="list-1">
                                                    <div className="deal-offer-contain">
                                                        <a href="shop-left-sidebar.html" className="deal-image">
                                                            <Image src="/assets/images/vegetable/product/13.png" className="blur-up lazyload"
                                                                width={100}  height={100}  alt="" />
                                                        </a>

                                                        <a href="shop-left-sidebar.html" className="deal-contain">
                                                            <h5>Blended Instant Coffee 50 g Buy 1 Get 1 Free</h5>
                                                            <h6>$52.57 <del>57.62</del> <span>500 G</span></h6>
                                                        </a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {/* deal-box Modal Start */}


                        {/* Location Modal Start */}
                        <div className="modal location-modal fade theme-modal" id="locationModal" tabIndex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div className="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
                        
                                <div className="modal-content">
                                    <div className="modal-header p-0">
                                        <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            <i className="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                    <div className="modal-body pt-0">
                                        <div className="location-list">
                                            <div className="disabled-box">
                                                <h6> My location OR Select a area</h6>
                                            </div>
                                
                                            <div className='row'>
                                                <div className="col-xxl-12 col-xl-12 mb-3">
                                                <div className="form-floating theme-form-floating">
                                                    <select defaultValue='0' className="form-control" id="shipping_division" name="shipping_division" onChange={handleshowAddress}>
                                                    <option  value='0' selected> --Select Division--</option>
                                                    {showAddress.divisions?.map((division, i) => (
                                                        <>
                                                        {showAddress.selected_division == division.id ? (
                                                            <option key={i} selected value={division.id}> {division.title}</option>
                                                        ):(
                                                            <option key={i}  value={division.id}> {division.title}</option>
                                                        )}
                                                        </>
                                                    ))}
                                                    </select>
                                                    <label htmlFor="old_password">Division <span className='mendatoryStar'>*</span></label>
                                                    <small className='errormessage shipping_division'></small>

                                                </div>
                                                </div>
                                                <div className="col-xxl-12 col-xl-12 mb-3">
                                                    <div className="form-floating theme-form-floating">
                                                        <select defaultValue="0" className="form-control" id="shipping_district" name="shipping_district" onChange={handleshowAddress}>
                                                        <option  value='0' selected> --Select District--</option>
                                                        
                                                        {showAddress?.districts?.map((data, i) => (
                                                            <>
                                                            {showAddress.selected_district == data.id ? (
                                                                <option key={i} selected value={data.id}> {data.title}</option>
                                                            ):(
                                                                <option key={i}  value={data.id}> {data.title}</option>
                                                            )}
                                                            </>
                                                        ))}
                                                        </select>
                                                        <label htmlFor="district">District <span className='mendatoryStar'>*</span></label>
                                                        <small className='errormessage shipping_district'></small>
                                                    </div>
                                                </div>
                                                <div className="col-xxl-12 col-xl-12 mb-3">
                                                    <div className="form-floating theme-form-floating">
                                                        <select defaultValue="0" className="form-control" id="shipping_thana" name="shipping_thana" onChange={handleshowAddress}>
                                                        <option  value='0' selected> --Select district first--</option>
                                                        
                                                        {showAddress?.upazilas?.map((data, i) => (
                                                            <>
                                                            {showAddress.selected_upazila == data.id ? (
                                                                <option key={i} selected value={data.id}> {data.title}</option>
                                                            ):(
                                                                <option key={i}  value={data.id}> {data.title}</option>
                                                            )}
                                                            </>
                                                        ))}
                                                        </select>
                                                        <label htmlFor="shipping_thana">Upazila <span className='mendatoryStar'>*</span></label>
                                                        <small className='errormessage shipping_thana'></small>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div className="modal-footer">
                                        <button type="button" className="btn btn-animation btn-md fw-bold"  data-bs-dismiss="modal">Close</button>
                                        <button onClick={location_submit} data-bs-dismiss="modal" className="btn theme-bg-color btn-md fw-bold text-light">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {/* Location Modal End  */}
                        {/* popupLogin Modal start  */}
                        <div className="modal fade theme-modal" id="popupLogin"  tabIndex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div className="modal-dialog modal-md">
                                <div className="input-box">
                                    <form className="row g-4" onSubmit={popupLoginSubmit}>
                                        <div className="modal-content">
                                            <div className="modal-header">
                                                <h5 className="modal-title" id="exampleModalLabel2">Login First</h5>
                                                <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                    <i className="fa-solid fa-xmark"></i>
                                                </button>
                                            </div>
                                            <div className="modal-body">
                                            <div className="col-12 mb-4">
                                                <div className="form-floating theme-form-floating log-in-form">
                                                    <Input type="text" name="phone" value={loginData2.phone} onChange={handleChange2} className="form-control" id="phone" placeholder="Phone/Email" />
                                                    <label htmlFor="phone">Phone/Email</label>
                                                </div>
                                                <small className='errormessage phone_error'></small>
                                            </div>

                                            <div className="col-12 mb-4">
                                                <div className="form-floating theme-form-floating log-in-form">
                                                    <Input name="password" value={loginData2.password} onChange={handleChange2} type="password" className="form-control" id="password"
                                                        placeholder="Password" />
                                                    <label htmlFor="password">Password</label>
                                                </div>
                                                <small className='errormessage password_error'></small>
                                            </div>

                                            <div className="col-12 mb-4">
                                                <div className="forgot-box">
                                                    <div className="form-check ps-0 m-0 remember-box">
                                                        <Input className="checkbox_animated check-box" type="checkbox"
                                                            id="flexCheckDefault" />
                                                        <label className="form-check-label" htmlFor="flexCheckDefault">Remember me</label>
                                                    </div>
                                                    <a href="forgot.html" className="forgot-password">Forgot Password?</a>
                                                </div>
                                            </div>
                                            
                                            <div className="col-12 mb-4">
                                                <small className='errormessage other_error'></small>
                                                <button className="btn btn-animation w-100 justify-content-center" type="submit">Log In</button>
                                            </div>
                                        </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {/* popupLogin Modal end  */}
                        


                    </div>
                </div>
            </div>
        </div>
        </header>







        </div>
    )
}


export async function getServerSideProps(context) {
    // Fetch data from an API
    const res = await fetch('http://127.0.0.1:8000/api/v1/site-info');
    const data = await res.json();
    console.log('kkkkkkkkk='+data);
    return {
      props: {
        data, // Pass data to the page component as props
      },
    };
  }



export default Header
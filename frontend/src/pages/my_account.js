import FrontendLayouts from '@/components/Layouts/Frontend/FrontendLayouts'
import withAuth from '@/components/Layouts/Frontend/withAuth';
import { useApi } from '@/components/Layouts/Frontend/swr-config';
import { useEffect, useState } from 'react';
import Image from 'next/image'
import Input from '@/components/Input';
import { useRouter } from 'next/router';
import axios from 'axios';
import { getSession, useSession } from 'next-auth/react';
import $ from "jquery"
import Link from 'next/link';
import { format } from 'date-fns';
import Login from './login';
import { ToastContainer, toast } from 'react-toastify';
import Meta from "@/components/Meta";

const My_account = () => {
    const router = useRouter();
    const { data:userdata, status, update, session }= useSession();
    const [imageSelected, setImage] = useState(null);
    const [createObjectURL, setCreateObjectURL] = useState(null);
    const uploadToClient = (event) => {
        if (event.target.files && event.target.files[0]) {
          const i = event.target.files[0];
          console.log('imageSelected==', i);
          setImage(i);
          setCreateObjectURL(URL.createObjectURL(i));
        }
    }
    const [orders, setOrders] = useState(null);
    const userorders = async () => {
        try {
            const token = localStorage.getItem('token');
            if(token){
                const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-user-orders`,{
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                const data = await response.json();
                setOrders(data);
            }
        } catch (error) {
            console.log('User order catch error');
        }
    }
    
    const [allAddress, setAddress] = useState(null);
    const getAllAddress = async () => {
        try {
            const token = localStorage.getItem('token');
            if(token){
                const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/alladdress`,{
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                const data = await response.json();
                setAddress(data);
            }
        } catch (error) {
            console.log('User order catch error');
        }
    }

    useEffect(() => {
        $('#selectedIamge').attr('src', createObjectURL);
        userorders();
        getAllAddress();
    }, []); 

    const [updateData, setupdateData] = useState({
        name: userdata?.customer.name,
        image: imageSelected,
    });


    const handleChange = (e) => {
        const { name, value } = e.target;
        setupdateData((prevData) => ({ ...prevData, [name]: value }));
    }

    const updateSubmit = async (e) => {
        e.preventDefault();
        const token = localStorage.getItem('token');
        let formData = new FormData();
        formData.append('name',updateData.name);
        formData.append('image', imageSelected);
        const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/update-user-details`, formData, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        if (response.data.status == 0) {
            response.data.message.name ? $('.name_error').text(response.data.message.name) : $('.name_error').text('');
        }else if(response.data.status == 2){
            $('.other_error').text(response.data.message);
        }else{
            $('.errormessage').text('');
            $('.success_message').text('Profile updated successfully.');
            update({ 
                customer_data: response.data.user
            });
        }
    }


    const [changePassword, setPasswordData] = useState({
        old_password: '',
        new_password: '',
        retype_password: '',
    });
    const passwordChange = (e) => {
        const { name, value } = e.target;
        setPasswordData((prevData) => ({ ...prevData, [name]: value }));
    }

    const changePasswordSubmit = async (e) => {
        e.preventDefault();
        const token = localStorage.getItem('token');
        const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/change-password`, changePassword, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        if (response.data.status == 0) {
            $('.password_error').text(response.data.message);
        }else if(response.data.status == 1){
            $('.password_error').text('');
            $('.password_success').text('Password changed successfully.');
        }
    }


    const cancelOrder = async (order_id) => {
        try {
            const response = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/cancel-order/`+order_id,{
                headers: {
                    Authorization: `Bearer ${userdata?.accessToken}`,
                },
            });
            if (response.data.status == 0) {
                toast.error(response.data.message, {
                    position: "bottom-right",
                    autoClose: 2000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: false,
                    progress: undefined,
                    theme: "light",
                });
            }else if(response.data.status == 1){
                userorders();
                toast.success(response.data.message, {
                    position: "bottom-right",
                    autoClose: 2000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: false,
                    progress: undefined,
                    theme: "light",
                });
            }
        } catch (error) {
            console.log('error ', error);
        }
    }

    
    const completeOrder = async (order_details_id) => {
        try {
            const response = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/product-recieve-confirmation/`+order_details_id,{
                headers: {
                    Authorization: `Bearer ${userdata?.accessToken}`,
                },
            });
            if (response.data.status == 0) {
                toast.error(response.data.message, {
                    position: "bottom-right",
                    autoClose: 2000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: false,
                    progress: undefined,
                    theme: "light",
                });
            }else if(response.data.status == 1){
                userorders();
                toast.success(response.data.message, {
                    position: "bottom-right",
                    autoClose: 2000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: false,
                    progress: undefined,
                    theme: "light",
                });
            }
        } catch (error) {
            console.log('error ', error);
        }
    }
    
    const [orderDetailsData, setorderDetailsData] = useState(null);
    const orderDetails = async (order_id) => {
        try {
            const response = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-single-order/`+order_id,{
                headers: {
                    Authorization: `Bearer ${userdata?.accessToken}`,
                },
            });
            
            if (response.data.status == 0) {
                toast.error(response.data.message, {
                    position: "bottom-right",
                    autoClose: 2000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: false,
                    progress: undefined,
                    theme: "light",
                });
            }else if(response.data.status == 1){
                setorderDetailsData(response.data);
                $('.orderDetailsModal').trigger('click');
            }
        } catch (error) {
            console.log('error ', error);
        }
    }

    const chnageDefultAdress = async (address_id) => {
        try {
            let formData = new FormData();
			formData.append('address_id', address_id);
			formData.append('pickpoint', 0);
            const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/update-default-address/`, formData,{
                headers: {
                    Authorization: `Bearer ${userdata?.accessToken}`,
                },
            });
            if (response.data.status == 0) {
                toast.error(response.data.message, {
                    position: "bottom-right",
                    autoClose: 2000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: false,
                    progress: undefined,
                    theme: "light",
                });
            }else if(response.data.status == 1){
                getAllAddress();
                toast.success(response.data.message, {
                    position: "bottom-right",
                    autoClose: 2000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: false,
                    progress: undefined,
                    theme: "light",
                });
            }
        } catch (error) {
            console.log('error ', error);
        }
    }
    const deleteAddress = async (address_id) => {
        try{
            const response = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/delete-address/`+address_id,{
                headers: {
                    Authorization: `Bearer ${userdata?.accessToken}`,
                },
            });
            if(response.data.status == 1){
                getAllAddress();
                toast.success(response.data.message, {
                    position: "bottom-right",
                    autoClose: 2000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: false,
                    progress: undefined,
                    theme: "light",
                });
            }
        }catch(error){
            console.log('error ', error);
        }
    }

    const [showAddress, setShowAddress] = useState({
        shipping_first_name:null,
        shipping_phone:null,
        shipping_email:null,
        shipping_postcode:null,
        shipping_address:null,
        
        selected_division:null,
        selected_district:null,
        selected_upazila:null,
        selected_union:null,

        divisions:[],
        districts:[],
        upazilas:[],
        unions:[],
        address_id:null,
    });
    const addressShowModal = async (address_id, action) => {
        if(action == 'add'){
            $('#shipping_first_name').val('');
            $('#shipping_phone').val('');
            $('#shipping_email').val('');
            $('#shipping_postcode').val('');
            $('#shipping_address').val('');
        }
        try {
            const response = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-single-address/`+address_id,{
                headers: {
                    Authorization: `Bearer ${userdata?.accessToken}`,
                },
            });
            const response_divisons = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-divisons`,{
                headers: {
                    Authorization: `Bearer ${userdata?.accessToken}`,
                },
            });
            setShowAddress({
                shipping_first_name:response.data?.singleAddress?.shipping_first_name,
                shipping_phone:response.data?.singleAddress?.shipping_phone,
                shipping_email:response.data?.singleAddress?.shipping_email,
                shipping_postcode:response.data?.singleAddress?.shipping_postcode,
                shipping_address:response.data?.singleAddress?.shipping_address,
                
                divisions:response_divisons.data?.divisons,
                districts:response.data?.selected_address?.district,
                upazilas:response.data?.selected_address?.upazila,
                unions:response.data?.selected_address?.union,
                
                selected_division:response.data?.singleAddress?.shipping_division,
                selected_district:response.data?.singleAddress?.shipping_district,
                selected_upazila:response.data?.singleAddress?.shipping_thana,
                selected_union:response.data?.singleAddress?.shipping_union,
                address_id:address_id,
            });
            $('.addressEidtModal').trigger('click');
            
        } catch (error) {
            console.log('error ', error);
        }
    }

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


    const updateShippingAddress = async (address_id) => {
        const token = localStorage.getItem('token');
        const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/add-new-address`, showAddress, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        if (response.data.status == 1) {
            $('.errormessage').text('');
            getAllAddress();
            $('.btn-close').trigger('click');
        }else{
            $('.errormessage').text('');
            var p = response.data.message;
            for (const [key, value] of Object.entries(p)) {
                $('.'+`${key}`).text(`${value}`);
              }

        }
    }

    return (
        <FrontendLayouts>
            <Meta title="My Account | Dhroobo" keywords="dhroobo keywords" decription="dhroobo decription" />
            {userdata?(
                <section className="user-dashboard-section section-b-space">
                    <div className="container-fluid-lg">
                        <div className="row">
                            <div className="col-xxl-3 col-lg-3">
                                <div className="dashboard-left-sidebar">
                                    <div className="close-button d-flex d-lg-none">
                                        <button className="close-sidebar">
                                            <i className="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                    <div className="profile-box">
                                        <div className="cover-image">
                                            <Image src="/assets/images/inner-page/cover-img.jpg" className="img-fluid blur-up lazyload"
                                                width={400}  height={200}  alt="" />
                                        </div>

                                        <div className="profile-contain">
                                            <div className="profile-image">
                                                <div className="position-relative">
                                                    <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${userdata?.customer.avatar}`}
                                                        className="blur-up lazyload update_img" width={100}  height={100}  alt="avatar" />
                                                    <div className="cover-icon" data-bs-toggle="modal" data-bs-target="#editProfile">
                                                        <i className="fa-solid fa-pen">
                                                        </i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div className="profile-name">
                                                <h3>{userdata?.customer.name}</h3>
                                                <h6 className="text-content">{userdata?.customer.email}</h6>
                                            </div>
                                        </div>
                                    </div>

                                    <ul className="nav nav-pills user-nav-pills" id="pills-tab" role="tablist">
                                        <li className="nav-item" role="presentation">
                                            <button className="nav-link active" id="pills-dashboard-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-dashboard" type="button" role="tab"
                                                aria-controls="pills-dashboard" aria-selected="true"><i data-feather="home"></i>
                                                DashBoard</button>
                                        </li>

                                        <li className="nav-item" role="presentation">
                                            <button className="nav-link" id="pills-order-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-order" type="button" role="tab" aria-controls="pills-order"
                                                aria-selected="false"><i data-feather="shopping-bag"></i>Order</button>
                                        </li>
                                        <li className="nav-item" role="presentation">
                                            <button className="nav-link" id="pills-card-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-card" type="button" role="tab" aria-controls="pills-card"
                                                aria-selected="false"><i data-feather="credit-card"></i> Shipping Address</button>
                                        </li>
                        

                                        <li className="nav-item" role="presentation">
                                            <button className="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-profile" type="button" role="tab"
                                                aria-controls="pills-profile" aria-selected="false"><i data-feather="user"></i>
                                                Profile</button>
                                        </li>

                                        <li className="nav-item" role="presentation">
                                            <button className="nav-link" id="pills-security-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-security" type="button" role="tab"
                                                aria-controls="pills-security" aria-selected="false"><i data-feather="shield"></i>
                                                Privacy</button>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div className="col-xxl-9 col-lg-9">
                                <button className="btn left-dashboard-show btn-animation btn-md fw-bold d-block mb-4 d-lg-none">Show
                                    Menu</button>
                                <div className="dashboard-right-sidebar">
                                    <div className="tab-content" id="pills-tabContent">
                                        <div className="tab-pane fade show active" id="pills-dashboard" role="tabpanel"
                                            aria-labelledby="pills-dashboard-tab">
                                            <div className="dashboard-home">
                                                <div className="title">
                                                    <h2>My Dashboard</h2>
                                                    <span className="title-leaf">
                                                        <svg className="icon-width bg-gray">
                                                            {/* <use xlink:href="https://themes.pixelstrap.com/fastkart/assets/svg/leaf.svg#leaf"></use> */}
                                                        </svg>
                                                    </span>
                                                </div>

                                        

                                                <div className="total-box">
                                                    <div className="row g-sm-4 g-3">
                                                        <div className="col-xxl-4 col-lg-4 col-md-4 col-sm-12">
                                                            <div className="totle-contain">
                                                                <Image src="https://themes.pixelstrap.com/fastkart/assets/images/svg/order.svg"
                                                                    className="img-1 blur-up lazyload" width={50}  height={50}  alt="" />
                                                                <Image src="https://themes.pixelstrap.com/fastkart/assets/images/svg/order.svg" className="blur-up lazyload"
                                                                    width={50}  height={50}  alt="" />
                                                                <div className="totle-detail">
                                                                    <h5>Total Order</h5>
                                                                    <h3>3658</h3>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div className="col-xxl-4 col-lg-4 col-md-4 col-sm-12">
                                                            <div className="totle-contain">
                                                                <Image src="https://themes.pixelstrap.com/fastkart/assets/images/svg/pending.svg"
                                                                    className="img-1 blur-up lazyload" width={50}  height={50}  alt="" />
                                                                <Image src="https://themes.pixelstrap.com/fastkart/assets/images/svg/pending.svg" className="blur-up lazyload"
                                                                    width={50}  height={50}  alt="" />
                                                                <div className="totle-detail">
                                                                    <h5>Total Pending Order</h5>
                                                                    <h3>254</h3>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div className="col-xxl-4 col-lg-4 col-md-4 col-sm-12">
                                                            <div className="totle-contain">
                                                                <Image src="https://themes.pixelstrap.com/fastkart/assets/images/svg/wishlist.svg"
                                                                    className="img-1 blur-up lazyload" width={50}  height={50}  alt="" />
                                                                <Image src="https://themes.pixelstrap.com/fastkart/assets/images/svg/wishlist.svg"
                                                                    className="blur-up lazyload" width={50}  height={50}  alt="" />
                                                                <div className="totle-detail">
                                                                    <h5>Total Wishlist</h5>
                                                                    <h3>32158</h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div className="row g-4 mt-2">
                                                    <div className="profile-about dashboard-bg-box">
                                                        <div className="row">
                                                            <div className="col-xxl-7">
                                                                <div className="dashboard-title mb-3">
                                                                    <div className='row'>
                                                                        <div className='col-md-12'>
                                                                            <span className='success_message'></span>
                                                                        </div>
                                                                        <div className='col-md-12'>
                                                                            <span className='other_error'></span>
                                                                        </div>
                                                                    </div>
                                                                    <div className="row">
                                                                        <div className="col-md-6">
                                                                            <h3>Profile About</h3>
                                                                        </div>
                                                                        <div className="col-md-6 profile_modal_btn">
                                                                            <Link href='#' data-bs-toggle="modal" data-bs-target="#editProfile">Edit</Link>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div className="table-responsive">
                                                                    <table className="table">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Name :</td>
                                                                                <td>{userdata?.customer.name}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Phone :</td>
                                                                                <td>{userdata?.customer.phone}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Email:</td>
                                                                                <td> {userdata?.customer.email}</td>
                                                                            </tr>
                                                                            {/* <tr>
                                                                                <td>Address :</td>
                                                                                <td>{userdata?.customer.default_address_id}</td>
                                                                            </tr> */}
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                                <div className="dashboard-title mb-3">
                                                                    <h3>Login Details</h3>
                                                                </div>

                                                                <div className="table-responsive">
                                                                    <table className="table">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Email :</td>
                                                                                <td>{userdata?.customer.email}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Password :</td>
                                                                                <td>
                                                                                    <Link href="#">●●●●●●
                                                                                        <span data-bs-toggle="modal"
                                                                                            data-bs-target="#changePassword">Edit</span></Link>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <div className='row'>
                                                                        <div className='col-md-12'>
                                                                            <span className='password_error'></span>
                                                                        </div>
                                                                        <div className='col-md-12'>
                                                                            <span className='password_success'></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="tab-pane fade" id="pills-order" role="tabpanel"
                                            aria-labelledby="pills-order-tab">
                                            <div className="dashboard-order">
                                                <div className="title">
                                                    <h2>All Order </h2>
                                                    <span className="title-leaf title-leaf-gray">
                                                        <svg className="icon-width bg-gray">
                                                            {/* <use xlink:href="https://themes.pixelstrap.com/fastkart/assets/svg/leaf.svg#leaf"></use> */}
                                                        </svg>
                                                    </span>
                                                </div>

                                                <div className="order-tab dashboard-bg-box">
                                                    <div className="table-responsive">
                                                        <table className="table order-table">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Order ID</th>
                                                                    <th scope="col">Date</th>
                                                                    <th scope="col">Total Amount</th>
                                                                    <th scope="col">Payment Status</th>
                                                                    <th scope="col">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                {orders?.orders.data.map((data, i) => (
                                                                <tr key={i}>
                                                                    <td className="product-image"> DR{data.id}</td>
                                                                    <td>
                                                                        <h6>{format(data.created_at, 'dd/MM/yyyy')} </h6>
                                                                    </td>
                                                                    <td>
                                                                        <h6>BDT {data.total_amount}</h6>
                                                                    </td>
                                                                    <td>
                                                                        <label className="success" style={{ background: data.status_color, color: '#fff' }}>{data.order_status}</label>
                                                                    </td>
                                                                    <td className='ordeA'>
                                                                        {data.status == 1  || data.status == 2  || data.status == 3 ?(
                                                                            <button onClick={() => cancelOrder(data.id)} className="btn btn-danger btn-sm mb-2">Cancel </button>
                                                                        ):('')}
                                                                        <button onClick={() => orderDetails(data.id)} className="btn btn-success btn-sm mb-2">Details </button>
                                                                    </td>
                                                                </tr>
                                                                ))}
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                
                                                </div>
                                            </div>
                                        </div>
                                        <div className="tab-pane fade show" id="pills-card" role="tabpanel"
                                            aria-labelledby="pills-card-tab">
                                            <div className="dashboard-card">
                                                <div className="title title-flex">
                                                    <div>
                                                        <h2>My Shipping Address</h2>
                                                        <span className="title-leaf">
                                                            <svg className="icon-width bg-gray">
                                                                {/* <use xlink:href="https://themes.pixelstrap.com/fastkart/assets/svg/leaf.svg#leaf"></use> */}
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <button onClick={() => addressShowModal(null, 'add')} className="btn theme-bg-color text-white btn-sm fw-bold mt-lg-0 mt-3">
                                                        <i data-feather="plus" className="me-2"></i> Add New Address</button>
                                                </div>

                                                <div className="row g-4">
                                                    <div className="order-tab dashboard-bg-box">
                                                        <div className="table-responsive" id='addresstable'>
                                                            <table className="table order-table" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="15%">Name</th>
                                                                        <th width="20%">Phone</th>
                                                                        <th width="35%">Address</th>
                                                                        <th width="15%">Default Address</th>
                                                                        <th width="25%">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    {allAddress?.address.map((address, i) => (
                                                                    <tr key={i}>
                                                                        <td>  { address.shipping_first_name }  { address.shipping_last_name }</td>
                                                                        <td>{ address.shipping_phone }</td>
                                                                        <td> { address.division?.title }, { address.district?.title }, { address.upazila?.title }, { address.union?.title } <br/> { address.shipping_address }</td>
                                                                        <td>
                                                                            {allAddress.user.default_address_id == address.id? (
                                                                                <div className="select_address" title="It is your default address"> </div>
                                                                            ):(
                                                                                <div className="unselect_address" title="Make this address default"> </div>
                                                                            )}
                                                                        </td>
                                                                        <td>
                                                                            <button type="button" className="btn btn-primary btn-sm mb-1 mr-1" onClick={() => chnageDefultAdress(address.id)}>Set Defalut</button> 
                                                                            <button type="button" className="btn btn-info btn-sm  mb-1 mr-1" onClick={() => addressShowModal(address.id, 'edit')}> Edit</button> 
                                                                            <button type="button" className="btn btn-danger btn-sm mb-1 mr-1" onClick={() => deleteAddress(address.id)}>Delete</button>
                                                                        </td>
                                                                    </tr>
                                                                    ))}
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            ):(
                <p>Loading...</p>
            )}

            <p data-bs-toggle="modal" data-bs-target="#orderDetailsModal" className='orderDetailsModal'></p>
            <p data-bs-toggle="modal" data-bs-target="#addressEidt" className='addressEidtModal'></p>
            <div className="modal fade theme-modal" id="orderDetailsModal" tabIndex="-1" aria-labelledby="exampleModalLabel3"
                    aria-hidden="true">
                <div className="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-sm-down">
                    <div className="modal-content">
                        <div className="modal-header">
                            <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i className="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div className="modal-body">
                        <div className="row">
                            <div className="col-md-6 mt-2">
                                <h5>Order Details</h5> 
                                <div className="single_order_details mb-3">
                                    <div className="card">
                                        <div className="card-body pb-0">
                                            {orderDetailsData?(
                                            <ul>
                                                <li><b>Order ID: </b> <span> DR{format(orderDetailsData.order.created_at, 'yy')}{orderDetailsData.order.id} </span></li> 
                                                <li><b>Date: </b> <span> {format(orderDetailsData.order.created_at, 'dd MMM yyyy')}</span></li>
                                                <li>
                                                    <b>Payment Status : </b> 
                                                    {orderDetailsData.statuses.map((data, i) => (
                                                        <span key={i}>
                                                        {data.id == orderDetailsData.order.status ? (
                                                            <span  style={{ background: data.color_code, color: '#fff', borderRadius:'4px', padding:'1px 5px' }}> {data.title} </span>
                                                        ):('')}
                                                        </span>
                                                    ))}
                                                </li>
                                                <li><b>Payment Method: </b> <span> {orderDetailsData.order.payment_method} </span></li> 
                                                {orderDetailsData.order.grocery_shipping_cost > 0? (<li><b>Grocery Shipping Cost: </b> <span>BDT {orderDetailsData.order.grocery_shipping_cost}  </span></li>):('')}
                                                <li><b>Total Shipping Cost: </b> <span>BDT {orderDetailsData.order.shipping_cost}  </span></li>
                                                
                                                

                                                {orderDetailsData.order.vat > 0? (<li><b>VAT: </b> <span>BDT {orderDetailsData.order.vat}  </span></li>):('')}
                                                {orderDetailsData.order.coupon_amount > 0? (<li><b>Coupon Discount: </b> <span>BDT {orderDetailsData.order.coupon_amount}  </span></li>):('')}
                                                {orderDetailsData.order.voucher_amount > 0? (<li><b>Voucher Discount: </b> <span>BDT {orderDetailsData.order.voucher_amount}  </span></li>):('')}
                                                

                                                <li><b>Total Amount: </b> <span>BDT {orderDetailsData.order.total_amount}  </span></li> 
                                                <li><b>Paid Amount: </b> <span>BDT {orderDetailsData.order.paid_amount}  </span></li> 
                                                {orderDetailsData.order.refunded > 0? (<li><b>Refunded Amount: </b> <span>BDT {orderDetailsData.order.refunded}  </span></li>):('')}
                                                {Number(orderDetailsData.order.total_amount) - Number(orderDetailsData.order.paid_amount) > 0? ( <li className="text-danger"><b>Due Amount: </b> <span>BDT {Number(orderDetailsData.order.total_amount) - Number(orderDetailsData.order.paid_amount)}  </span></li> ):('')}

                                                <li> <b>Note: </b> <span> { orderDetailsData.order.note } </span> </li>
                                                {orderDetailsData.order.is_pickpoint > 0? (<li><b className="badge badge-danger"> Pick Point Order</b></li>):('')}


                                            </ul>
                                            ):('')}
                                        </div>
                                    </div>
                                </div>
                            </div> 
                            <div className="col-md-6 mt-2">
                                <h5>Shipping information </h5> 
                                <div className="single_order_details mb-3">
                                    <div className="card"><div className="card-body pb-0">
                                        {orderDetailsData? (
                                        <ul>
                                            <li><b>Full Name : </b> <span> {orderDetailsData.shipping_address?.shipping_first_name}  {orderDetailsData.shipping_address?.shipping_last_name} </span></li> 
                                            <li><b>Division: </b> <span> {orderDetailsData.shipping_address?.division?.title} </span></li> 
                                            <li><b>District: </b> <span> {orderDetailsData.shipping_address?.district?.title} </span></li> 
                                            <li><b>Upazila / Thana : </b> <span> {orderDetailsData.shipping_address?.upazila?.title} </span></li> 
                                            <li><b>Union / Area: </b> <span> {orderDetailsData.shipping_address?.union?.title} </span></li> 
                                            <li><b>Post code: </b> <span> {orderDetailsData.shipping_address?.shipping_postcode} </span></li> 
                                            <li><b>Phone: </b> <span> {orderDetailsData.shipping_address?.shipping_phone} </span></li> 
                                            <li><b>Email: </b> <span> {orderDetailsData.shipping_address?.shipping_email} </span></li> 
                                            <li><b>Address: </b> <span> {orderDetailsData.shipping_address?.shipping_address} </span></li>
                                        </ul>
                                        ):('')}
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div className="single_order_details mb-4">
                            <div id="single_order_details" className="user-orders border_only">
                                <table width="100%" className="table table-bordered user-orders-full">
                                    <thead>
                                    <tr>
                                        <th width="22%">Product</th>
                                        <th width="10%">Price</th>
                                        <th width="5%"> Qty</th>
                                        <th width="10%"> Shipping Cost</th>
                                        <th width="11%"> Packaging Cost</th>
                                        <th width="11%"> Security Charge</th>
                                        <th width="10%"> Sub Total</th>
                                        <th width="11%">Shipping Status</th>
                                        <th width="10%"> Action</th>
                                    </tr>
                                    </thead> 
                                    {orderDetailsData?(
                                    <tbody>
                                    
                                    {orderDetailsData.products?.map((productData, key) => (
                                        <tr key={key}>
                                            <td className="text-left">
                                                <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${productData.product.default_image}`} alt="" width={120} height={100} />
                                                <Link href={`/product/${productData.product.slug}`}>
                                                    <p>{productData.product.title}</p>
                                                </Link>
                                                <b>SKU:</b> { productData.product_sku }
                                                <br/> 
                                                {productData.product.product_options ? (
                                                    <span>
                                                        {productData.product.product_type == 'variable' ? (
                                                            <span>
                                                            {productData.product.product_options?.map((vOption, key) => (
                                                                <p key={key} className="mb-0 text-capitalize font-13"> <b>{key}</b> : {vOption}</p>
                                                            ))}
                                                            </span>
                                                        ):('')}
                                            
                                                        {productData.product.product_type == 'variable' ? (
                                                            <span  v-if="productData.product.product_type == 'service'">
                                                            {productData.product.product_options?.map((vOption, key) => (
                                                                <p key={key} className="mb-0 text-capitalize font-13"> <b>{ key.replace('_',' ') }</b> : {vOption}</p>
                                                            ))}
                                                            </span>
                                                        ):('')}
                                                    </span>
                                                ):('')}

                                            </td> 
                                            <td>BDT { productData.product.price } </td> 
                                            <td> { productData.product.product_qty } </td> 
                                            <td> BDT { productData.product.shipping_cost } </td> 
                                            <td> BDT {  productData.product.packaging_cost ? productData.packaging_cost : 0   } </td> 
                                            <td> BDT { productData.product.security_charge ? productData.security_charge : 0  } </td> 
                                            <td>BDT {  ( Number(productData.price) * Number(productData.product_qty)) + Number(productData.shipping_cost)  } </td> 
                                            <td>
                                            
                                                {orderDetailsData.statuses.map((data, i) => (
                                                    <span key={i}>
                                                    {data.id == productData.status ? (
                                                        <span  style={{ background: data.color_code, color: '#fff', borderRadius:'4px', padding:'1px 5px' }}> {data.title} </span>
                                                    ):('')}
                                                    </span>
                                                ))}


                                            </td>
                                            <td>
                                            {productData.status == 10 ? (
                                                <> 
                                                <Link href={'return/'+orderDetailsData.order.id+','+productData.product.id}><button className="btn btn-primary sm mb-1" style="padding: 1px 10px;border-radius:4px;"> Return </button> </Link>
                                                <button title="I have accepted this product without any fault or loss" onClick={() => completeOrder(productData.id)} className="btn btn-success sm mb-1" style="padding: 1px 10px;border-radius:4px;"> Complete  </button> 
                                                </>
                                            ):('')}

                                            {orderDetailsData.order.status == 6 &&  productData.isDownloadable == 'downloadable' && productData.product.product_type == 'digital'? (
                                                <button onClick={() => downloadFile(productData.product_id, productData.file_extension)} className="btn btn-primary sm mb-1" style="padding: 1px 10px;border-radius:4px;">  Download </button>
                                            ):('')}
                                            {productData.product.allow_review == 1 && productData.status == 6 && productData.reviewed == 0? (
                                                <button data-toggle="modal" data-target=".staticBackdrop" className="btn btn-success sm mb-1 rate_now_button" style="padding: 1px 10px;border-radius:4px;">Rate Product  </button>
                                            ):('')}
                                            </td> 
                                        </tr>
                                    ))}
                                    </tbody>
                                    ):('')}
                                    </table>
                                    
                                </div> 
                            </div>
                        </div>
                        <div className="modal-footer">
                            <button type="button" className="btn btn-animation btn-md fw-bold"
                                data-bs-dismiss="modal">Close</button>
                            <button type="submit" data-bs-dismiss="modal"
                                className="btn theme-bg-color btn-md fw-bold text-light">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <div className="modal fade theme-modal" id="editProfile" tabIndex="-1" aria-labelledby="exampleModalLabel4"
                    aria-hidden="true">
                <div className="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down">
                    <form onSubmit={updateSubmit} encType='multipart/form-data'>
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title" id="exampleModalLabel2">Edit Profile</h5>
                                <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <i className="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <div className="modal-body">
                                <div className="row g-4">
                                    <div className="col-xxl-12">
                                        <div className="form-floating theme-form-floating">
                                            <Input type="text" className="form-control" id="pname" name="name" value={updateData.name}  onChange={handleChange}/>
                                            <label htmlFor="pname">Full Name</label>
                                        </div>
                                    </div>

                                    <div className="col-xxl-6">
                                            <div className="form-floating theme-form-floating">
                                                <Input type="text" className="form-control" id="email1" name="phone" disabled value={userdata?.customer.phone} />
                                                <label htmlFor="email1">Phone</label>
                                            </div>
                                    </div>

                                    <div className="col-xxl-6">
                                            <div className="form-floating theme-form-floating">
                                                <Input className="form-control" type="email" id="mobile" name="email" disabled value={userdata?.customer.email} />
                                                <label htmlFor="mobile">Email</label>
                                            </div>
                                    </div>

                        

                                    <div className="col-xxl-6">
                                        <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${userdata?.customer.avatar}`} id='selectedIamge' width={100} height={100} />
                                        <div className="form-floating theme-form-floating">
                                            <Input className="form-control"  type="file" name="image" onChange={uploadToClient}/>
                                            <label htmlFor="mobile">Image</label>
                                        </div>
                                    </div>


                                    

                                </div>
                            </div>
                            <div className="modal-footer">
                                <button type="button" className="btn btn-animation btn-md fw-bold"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" data-bs-dismiss="modal"
                                    className="btn theme-bg-color btn-md fw-bold text-light">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div className="modal fade theme-modal" id="changePassword" tabIndex="-1" aria-labelledby="exampleModalLabel5"
                    aria-hidden="true">
                    <div className="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down">
                        <form onSubmit={changePasswordSubmit} method='post'>
                            <div className="modal-content">
                                <div className="modal-header">
                                    <h5 className="modal-title" id="exampleModalLabel2">Change Password</h5>
                                    <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <i className="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                                <div className="modal-body">
                                    <div className="row g-4">
                                        <div className="col-xxl-12">
                                            <div className="form-floating theme-form-floating">
                                                <Input type="password" className="form-control" id="old_password" name="old_password" value={changePassword.old_password} onChange={passwordChange} />
                                                <label htmlFor="old_password">Old Password</label>
                                            </div>
                                        </div>

                                        <div className="col-xxl-6">
                                            <div className="form-floating theme-form-floating">
                                                <Input type="password" className="form-control" id="new_password" name="new_password" value={changePassword.new_password} onChange={passwordChange} />
                                                <label htmlFor="new_password">New Password</label>
                                            </div>
                                        </div>

                                        <div className="col-xxl-6">
                                            <div className="form-floating theme-form-floating">
                                                <Input className="form-control" type="password" id="retype_password" name="retype_password" value={changePassword.retype_password} onChange={passwordChange} />
                                                <label htmlFor="retype_password">Retype Password</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="modal-footer">
                                    <button type="button" className="btn btn-animation btn-md fw-bold"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" data-bs-dismiss="modal"
                                        className="btn theme-bg-color btn-md fw-bold text-light">Change</button>
                                </div>
                            </div>
                        </form>
                    </div>
            </div>
            <div className="modal fade theme-modal" id="addressEidt" tabIndex="-1" aria-labelledby="exampleModalLabel6"
                    aria-hidden="true">
                    <div className="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down">
                        <form method='post' className='addressform'>
                            <div className="modal-content">
                                <div className="modal-header">
                                    <h5 className="modal-title" id="exampleModalLabel2">Add/Edit Address</h5>
                                    <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                        <i className="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                                <div className="modal-body">
                                    <div className="row g-4">
                                        <div className="col-xxl-6 col-xl-6">
                                            <div className="form-floating theme-form-floating">
                                                <Input type="text" className="form-control" id="shipping_first_name" name="shipping_first_name" value={showAddress.shipping_first_name} onChange={handleshowAddress} />
                                                <label htmlFor="old_password">Full Name <span className='mendatoryStar'>*</span> </label>
                                                <small className='errormessage shipping_first_name'></small>
                                            </div>
                                        </div>
                                        <div className="col-xxl-6 col-xl-6">
                                            <div className="form-floating theme-form-floating">
                                                <Input type="text" className="form-control" id="shipping_phone" name="shipping_phone" value={showAddress.shipping_phone} onChange={handleshowAddress} />
                                                <label htmlFor="old_password">Phone <span className='mendatoryStar'>*</span></label>
                                                <small className='errormessage shipping_phone'></small>

                                            </div>
                                        </div>
                                        <div className="col-xxl-6 col-xl-6">
                                            <div className="form-floating theme-form-floating">
                                                <Input type="email" className="form-control" id="shipping_email" name="shipping_email" value={showAddress.shipping_email} onChange={handleshowAddress} />
                                                <label htmlFor="old_password">Email</label>
                                            </div>
                                        </div>
                                        <div className="col-xxl-6 col-xl-6">
                                            <div className="form-floating theme-form-floating">
                                                <select defaultValue="0" className="form-control" id="shipping_division" name="shipping_division" onChange={handleshowAddress}>
                                                <option  value='0' selected> --Select Division--</option>
                                                {showAddress.divisions?.map((division, i) => (
                                                    <>
                                                    {showAddress.selected_division == division.id ? (
                                                        <option selected value={division.id}> {division.title}</option>
                                                    ):(
                                                        <option  value={division.id}> {division.title}</option>
                                                    )}
                                                    </>
                                                ))}
                                                </select>
                                                <label htmlFor="old_password">Division <span className='mendatoryStar'>*</span></label>
                                                <small className='errormessage shipping_division'></small>

                                            </div>
                                        </div>
                                        <div className="col-xxl-6 col-xl-6">
                                            <div className="form-floating theme-form-floating">
                                                <select defaultValue="0" className="form-control" id="shipping_district" name="shipping_district" onChange={handleshowAddress}>
                                                <option  value='0' selected> --Select District--</option>
                                                
                                                {showAddress?.districts?.map((data, i) => (
                                                    <>
                                                    {showAddress.selected_district == data.id ? (
                                                        <option selected value={data.id}> {data.title}</option>
                                                    ):(
                                                        <option  value={data.id}> {data.title}</option>
                                                    )}
                                                    </>
                                                ))}
                                                </select>
                                                <label htmlFor="district">District <span className='mendatoryStar'>*</span></label>
                                                <small className='errormessage shipping_district'></small>
                                            </div>
                                        </div>
                                        <div className="col-xxl-6 col-xl-6">
                                            <div className="form-floating theme-form-floating">
                                                <select defaultValue="0" className="form-control" id="shipping_thana" name="shipping_thana" onChange={handleshowAddress}>
                                                <option  value='0' selected> --Select district first--</option>
                                                
                                                {showAddress?.upazilas?.map((data, i) => (
                                                    <>
                                                    {showAddress.selected_upazila == data.id ? (
                                                        <option selected value={data.id}> {data.title}</option>
                                                    ):(
                                                        <option  value={data.id}> {data.title}</option>
                                                    )}
                                                    </>
                                                ))}
                                                </select>
                                                <label htmlFor="shipping_thana">Upazila <span className='mendatoryStar'>*</span></label>
                                                <small className='errormessage shipping_thana'></small>

                                            </div>
                                        </div>
                                        <div className="col-xxl-6 col-xl-6">
                                            <div className="form-floating theme-form-floating">
                                                <select defaultValue="0" className="form-control" id="shipping_union" name="shipping_union" onChange={handleshowAddress}>
                                                <option  value='0' selected> --Select upazila first--</option>
                                                
                                                {showAddress?.unions?.map((data, i) => (
                                                    <>
                                                    {showAddress.selected_union == data.id ? (
                                                        <option selected value={data.id}> {data.title}</option>
                                                    ):(
                                                        <option  value={data.id}> {data.title}</option>
                                                    )}
                                                    </>
                                                ))}
                                                </select>
                                                <label htmlFor="shipping_union">Union / Area <span className='mendatoryStar'>*</span></label>
                                                <small className='errormessage shipping_union'></small>
                                            </div>
                                        </div>
                                        <div className="col-xxl-6 col-xl-6">
                                            <div className="form-floating theme-form-floating">
                                                <Input type="text" className="form-control" id="shipping_postcode" name="shipping_postcode" value={showAddress.shipping_postcode} onChange={handleshowAddress} />
                                                <label htmlFor="shipping_postcode">Post code</label>
                                            </div>
                                        </div>

                                        <div className="col-xxl-6 col-xl-12">
                                            <div className="form-floating theme-form-floating">
                                                <Input type="text" className="form-control" id="shipping_address" name="shipping_address" value={showAddress.shipping_address} onChange={handleshowAddress} />
                                                <label htmlFor="shipping_address">Address <span className='mendatoryStar'>*</span></label>
                                                <small className='errormessage shipping_address'></small>
                                            </div>
                                        </div>
                                        

                                    </div>
                                </div>
                                <div className="modal-footer">
                                    <button type="button" className="btn btn-animation btn-md fw-bold"
                                        data-bs-dismiss="modal">Close</button>
                                    <p onClick={() => updateShippingAddress(showAddress.address_id)}
                                        className="btn theme-bg-color btn-md fw-bold text-light">Change</p>
                                </div>
                            </div>
                        </form>
                    </div>
            </div>
            
        </FrontendLayouts>
    )




}

export default withAuth(My_account);
//export default My_account;

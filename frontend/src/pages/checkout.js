import FrontendLayouts from '@/components/Layouts/Frontend/FrontendLayouts'
import Image from 'next/image';
import { useSelector, useDispatch } from 'react-redux';
import { AddToCart, InitCart, removeItem, updateQty, selectTotalQuantity } from '../lib/features/cart/cartSlice';
import Link from 'next/link';
import { useEffect, useState } from 'react';
import RemoveCartBtn from "@/components/Buttons/RemoveCartBtn";
import { getSession, useSession, signIn, signOut  } from 'next-auth/react';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Input from '@/components/Input';
import axios from 'axios';
import { useRouter } from 'next/router';


const CartPage = () => {
  const [loading, setLoading] = useState(false);
  const { data:userdata, status }= useSession();
  const cart = useSelector((state) => state.cart);
  const initcart = useSelector((state) => state.cart?.initcart);
  const dispatch = useDispatch();
  const totalQuantity = useSelector(selectTotalQuantity);
  const router = useRouter();


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

  const handleupdateQty = async (rowId, update) => {
    setLoading(true);
    const data = {rowId:rowId, update:update}
    try {
      await dispatch(updateQty(data));
      await dispatch(InitCart());
      calculateFinalAmount();
      setLoading(false);
    } catch (error) {
      console.error('Failed to add item to cart', error);
      setLoading(false);
    }
  }
  
const handleChange = (e) => {
    const { name, value } = e.target;
    setupdateData((prevData) => ({ ...prevData, [name]: value }));
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
            await dispatch(InitCart());
            calculateFinalAmount();
            $('.btn-close').trigger('click');
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
      await dispatch(InitCart());
      calculateFinalAmount();
      $('.btn-close').trigger('click');
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

  }else{
      $('.errormessage').text('');
      var p = response.data.message;
      for (const [key, value] of Object.entries(p)) {
          $('.'+`${key}`).text(`${value}`);
        }
  }
}


const [coupon_discount, setCoupon_discount] = useState([]);
const applyCouponCode = async() => {
    const token = localStorage.getItem('token');
    let formData = new FormData();
   	formData.append('coupon', $('#couponeCode').val());
    const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-coupon-amount`, formData, {
        headers: {
            Authorization: `Bearer ${token}`,
        },
    });
    if (response.data.status == 1) {
        setCoupon_discount(response.data);
        $('.coupon_discount').attr('data-coupon-discount',response.data.amount);
        $('#addCouponBlock').hide();
        $('.coupon_discount').show();
        calculateFinalAmount();
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
    }else{
        // console.log('applyCouponCode error.');
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
    }
}
const removeCoupon = async () => {
    $('#addCouponBlock').show();
    $('.coupon_discount').hide();
    $('.coupon_discount').attr('data-coupon-discount',0)
    setTimeout(function(){ 
        calculateFinalAmount();
     },200);
  }


const [finalCalculatedTotal, setFinalTotal] = useState([]);
const calculateFinalAmount = async () => {
    let totalShippingCost = Number(0);
    let pickPointCost = Number($('#pickPointCost').attr('data-pickpoint-cost')) > 0 ? Number($('#pickPointCost').attr('data-pickpoint-cost')) : 0;
    if(pickPointCost > 0){
        totalShippingCost = Number(pickPointCost);
    }else{
        $('.select_shipping_options .selected_shipping').each(function(key, val) {
            let singleShipping = Number($(this).attr('data-shipping-cost'));
            let singleQty = Number($(this).attr('data-qty'));
            totalShippingCost += Number((Number(singleShipping) * Number(singleQty)));
        });
        let grocery_shipping = Number($('.grocery_shipping_cost').attr('data-shipping-cost')) > 0 ? Number($('.grocery_shipping_cost').attr('data-shipping-cost')):0;
        if(grocery_shipping){
            totalShippingCost = Number(totalShippingCost) + Number(grocery_shipping);
        }
    }
    let total_packaging_cost = Number($('.data_packaging_cost').attr('data-packaging-cost-amount')) > 0 ? Number($('.data_packaging_cost').attr('data-packaging-cost-amount')):0;
    let total_security_charge = Number($('.data_security_charge').attr('data-security-charge-amount')) > 0 ? Number($('.data_security_charge').attr('data-security-charge-amount')):0;
    let data_sub_total = Number($('.data_sub_total').attr('data-subtotal-amount'));
    let coupon_discount = Number($('.coupon_discount').attr('data-coupon-discount')) > 0 ? Number($('.coupon_discount').attr('data-coupon-discount')) : 0;
    let voucher_discount = 0;
    if(coupon_discount > 0){
        $('.coupon_discount').show();
    }
    if(voucher_discount > 0){
        $('.show_voucher_discount').show();
    }
    let data_discount = coupon_discount+voucher_discount;
    let data_vat = Number($('.data_vat').attr('data-vat-amount')) > 0 ? Number($('.data_vat').attr('data-vat-amount')) : 0;
    let total = (data_sub_total + totalShippingCost +total_packaging_cost+total_security_charge+data_vat) - data_discount;
    $('.shipping_cost_li').attr('data-shipping-cost', totalShippingCost)
    $('.calculatedTotal').html(total);
    $('.calculatedShipping').html(totalShippingCost);
    console.log('totalShippingCost='+totalShippingCost+' data_discount='+data_discount+' data_vat='+data_vat+' total='+total+' total_packaging_cost='+total_packaging_cost+' total_security_charge='+total_security_charge);
    setFinalTotal(total);
}


const proceedToPay = async() => {
    if(userdata){ 
        let token = localStorage.getItem("token");
        if(allAddress?.user?.default_address_id < 1){
            $('.address_btn').trigger('click');
            return true;
        }

        if ($('.agree').is(':checked')) {
            let shipping_method  = [];
            $('.select_shipping_options .selected_shipping').each(function(key,val){
                shipping_method[key] = { 
                    product_id : $(this).attr('data-product-id'),
                    shipping_method: $(this).attr('data-shipping-method'),
                    shipping_cost: $(this).attr('data-shipping-cost'),
                }
            });
            let formData = {
                note: $('.form_note').val(),
                coupon: $('#couponeCode').val(),
                partial_payment: $('#partial_payment').val(),
                payment_method : $('.paymentmethod .selected_payment').attr('data-payment-method'),
                shipping_method : shipping_method
            }

            $('.proceed_to_pay').attr('disabled', true);
            //$('.proceed_to_pay').html('<span className="spinner-border spinner-border-sm"></span>');
            const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/order`, formData, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });

            if(response.data.status == 1){
                $('.proceed_to_pay').attr('disabled', false);
                //$('.proceed_to_pay').html('Proceed To Pay');
                $('.cart_close').trigger('click');
                getAllAddress();
                await dispatch(InitCart());
                calculateFinalAmount();

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

                // this.$store.dispatch('loadedUser');
                // this.$store.dispatch('loadedCart');
                // this.$store.dispatch('loadedNotifications');
                // this.$router.push({name:'orderDetails',params: {id: response.data.invoice.order_id } });
                router.push('/my_account');
            }else if(response.data.status == 2){
            
                $('.proceed_to_pay').attr('disabled', false);
                $('.proceed_to_pay').html('Proceed To Pay');
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


                // this.$store.dispatch('loadedUser');
                // this.$store.dispatch('loadedCart');
                // this.$store.dispatch('loadedNotifications');
                getAllAddress();
                await dispatch(InitCart());
                calculateFinalAmount();
            }else if( response.data.status == 302){
                //this.$store.dispatch('loadedUser');
                window.location.href = response.data.url;
                console.log(response.data.status);

            }else if(response.data.status == 0){
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
                $('.proceed_to_pay').attr('disabled', false);
                $('.proceed_to_pay').html('Proceed To Pay');
            }else{
                toast.error('Order Failed! Please try again later', {
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
        }else{
            toast.error('Please accept terms and conditions !', {
                position: "bottom-right",
                autoClose: 3000,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: false,
                progress: undefined,
                theme: "light",
            });
        }

    }else{
        toast.error('Please login first.', {
            position: "bottom-right",
            autoClose: 2000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: false,
            progress: undefined,
            theme: "light",
        });
        $('.popupLogin').trigger('click');
        return true;
    }
}

// const handleChange2 = (e) => {
//     const { name, value } = e.target;
//     setloginData((prevData) => ({ ...prevData, [name]: value }));
// }
// const [loginData2, setloginData] = useState({
//     phone: '',
//     password: '',
// });
// const popupLoginSubmit = async (e) => {
//     e.preventDefault();
//     const phone = loginData2.phone;
//     const password = loginData2.password;
//     const result = await signIn('credentials', {
//         phone,
//         password,
//         redirect: false,
//     });
  
//     if(result.error) {
//         $('.other_error').text('Error ! Invalid credentials.');
//       } else {
//         getAllAddress();
//         await dispatch(InitCart());
//         calculateFinalAmount();
//         $('.btn-close').trigger('click');
//     }
// }

    useEffect(() => {
        getAllAddress();
        dispatch(InitCart());
        if(userdata){
            setTimeout(function(){ 
                calculateFinalAmount();
            },5000);
        }
    }, []); 

  return (
    <FrontendLayouts> 
    <div className="container">
      {totalQuantity < 1 ? (
        <div className='empty_cart'><h4>Your cart is empty !</h4></div>
        
      ) : (
        <>
            {initcart?.sub_total > 0 ? (
            <section id="cart-page">
                <div className="container">
                    <div className="row cart-page-container">
                        <div className="col-12 col-sm-12 col-md-8 col-lg-8 pr-0">
                            <div className="row shoping-cart-text">
                                <div className="col-12 col-sm-12 col-md-12">
                                    <h4>Checkout</h4>
                                </div>
                            </div>
                            <div className="cart-calculation">
                            <table  className="table text-left cart_table" width="100%">
                                  <thead>
                                    <tr>
                                      <th width="65%">Product Details</th>
                                      <th width="25%"> Price</th>
                                      <th className="text-center" width="10%"> Quantity</th>
                                    </tr>
                                  </thead>
                                        
                                  {initcart?.cart?.map((cartgroup, index1) => (
                                    <tbody key={index1}>
                                      
                                      <tr className="group_header mb-3">
                                        <td colSpan="7">
                                          <small> Shipped by : <b> <Link className="text-dark" href={`/shop/${cartgroup.shop_info.shop_slug}`}>{cartgroup.shop_info.shop_name}</Link></b></small>
                                        </td>
                                      </tr> 
                                      {cartgroup.items?.map((cart, index2) => (
                                      <tr key={index2} className="cart_product_group">
                                        <td> 
                                          <div className="table-item">
                                              <div className="media">
                                                <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${cart.product.default_image}`} alt="" width={120} height={100} />
                                                  <div className="media-body">
                                                      <h5 className="mt-0"> <Link href={`/product/${cart.product.slug}`}> {cart.product.title} </Link>
                                                      
                                                        {cart.product.is_grocery == 'grocery' ?(
                                                            <span className="grocery_shipping_cost badge" data-shipping-cost={initcart.grocery_shipping_cost}>Grocery</span>
                                                        ):('')}
                                                      </h5>
                                                      

                                                      {cart.product.is_grocery != 'grocery' && cart.product_type != 'digital' && cart.product_type != 'service' && userdata?.customer?.default_address_id > 0 ? (
                                                      <div  className="select_shipping_options">
                                                          {cart.shipping_options != 0  && initcart?.pickpoint == 0 ? (
                                                          <ul  className="list-group list-group-horizontal">
                                                              {cart.shipping_options.free_shipping == 'on' ? (
                                                                <li data-product-id={cart.product_id}  data-shipping-method='free_shipping'  data-shipping-cost={0}  data-qty={cart.qty} className="list-group-item"> BDT 0 <br/> Free Shipping  <br/>  
                                                                {/* Est. Arrival: Within 7 to 15 days  */}
                                                                </li>
                                                              ):('')}

                                                              {cart?.shipping_options?.standard_shipping > 0 ? (
                                                              <li data-product-id={cart.product_id} data-shipping-method="standard_shipping"  data-shipping-cost={cart?.shipping_options?.standard_shipping}  data-qty={cart.qty} className="list-group-item selected_shipping" >BDT { cart?.shipping_options?.standard_shipping }  <br/>Standard Shipping  
                                                              {/* <br/>  Est. Arrival: Within 4 to 7 days  */}
                                                              </li>
                                                              ):('')}
                                                              {cart.shipping_options.express_shipping > 0 ? (
                                                              <li data-product-id={cart.product_id} data-shipping-method="express_shipping"  data-shipping-cost={cart?.shipping_options?.express_shipping}  data-qty={cart.qty} className="list-group-item">BDT { cart?.shipping_options?.express_shipping } <br/> Express Shipping  
                                                              {/* <br/> Est. Arrival: Within 1 to 3 days  */}
                                                              </li>
                                                              ):('')}
                                                          </ul>
                                                          ):('')}
                                                            {initcart?.pickpoint == 1 ? (
                                                                <span id="pickPointCost" data-pickpoint-cost={initcart?.shipping_cost} ></span>
                                                            ):('')}



                                                      </div>
                                                      ):('')}


                                                      {cart.product_type == 'variable' ? (
                                                        <span>
                                                            {cart.variable_options?.map((vOption, key) => (
                                                            <p className="mb-0 text-capitalize font-13" key={key}> <b>{key}</b> : {vOption}</p>
                                                            ))}
                                                        </span>
                                                      ):('')}


                                                      {cart.product_type == 'digital' ? (
                                                      <span>
                                                          {cart.variable_options ? (
                                                          <p className="mb-0 text-capitalize font-13"> <b>Contact Number</b> : {cart.variable_options}</p>
                                                          ):('')}
                                                      </span>
                                                      ):('')}

                                                  </div>
                                              </div>
                                          </div> 
                                        </td>
                                        <td> <div className="table-item">BDT { cart.price }</div> </td>
                                        <td className="text-center" > <div className="table-item">{ cart.qty }</div> </td>
                                      </tr>
                                      ))}
                                    </tbody>
                                  ))}

                                </table>
                            </div>
                        </div>







                        <div className="col-12 col-sm-12 col-md-4 col-lg-4 payment" id='payment_checkout'>
                            <b>Shipping information</b>
                            <span className="address_btn" data-bs-toggle="modal" data-bs-target="#addressModal"></span>
                            <a data-bs-toggle="modal" data-bs-target="#popupLogin" className="popupLogin"></a>
                            {allAddress ? (
                              <div className="address_details">
                                {allAddress?.address?.map((address, i) => (
                                  <ul  key={i}>
                                    {allAddress?.user?.default_address_id == address?.id ? (
                                      <>
                                      <li> 
                                          <div className="row p-0">
                                              <div className="col-lg-1 pr-0"><b><i className="fa fa-map-marker" aria-hidden="true"></i></b></div>
                                              <div className="col-lg-10 p-0"> <span>{ address?.division?.title }, { address?.district?.title }, { address?.upazila?.title }, { address?.union?.title } <br/> { address?.shipping_address }</span> </div>
                                              <div className="col-lg-1 pl-0"><i className="fa fa-pencil address_btn" data-bs-toggle="modal" data-bs-target="#addressModal" aria-hidden="true"></i></div>
                                          </div>
                                      </li>
                                      <li> <b><i className="fa fa-phone" aria-hidden="true"></i></b> <span>{ address?.shipping_phone }</span></li>
                                      {address?.shipping_email ? (
                                        <li> <b><i className="fa fa-envelope-o" aria-hidden="true"></i></b> <span> { address?.shipping_email } </span> </li>
                                      ):('')}
                                      </>
                                      ):('')}
                                    </ul>   
                                  ))} 
                              </div>
                            ):(
                              <div  className="address_details_alt"> 
                                <div className="row p-0">
                                    <div className="col-lg-1 pr-0"><b><i className="fa fa-map-marker" aria-hidden="true"></i></b></div>
                                    {allAddress?.user?.default_address_id < 1 ? (
                                    <div className="col-lg-10 p-0"> <p className="required_addtess" data-required-address="true">You need to select your default shipping address.</p> </div>
                                    ):('')}
                                    
                                    <div className="col-lg-1 pl-0"><i className="fa fa-pencil address_btn" data-toggle="modal" data-target="#addressModal" aria-hidden="true"></i></div>
                                </div>  
                              </div>
                            )}


                            <div className="note">
                                <textarea type="text" v-model="note" className="form-control form_note" rows="3" placeholder="Write a note here"></textarea>
                                <div id="addCouponBlock">
                                    <b className="mt-3">Add Coupon </b>
                                    <div className="input-group mb-3">
                                        <input id="couponeCode" type="text" className="form-control" placeholder="Write a coupon code here"  aria-describedby="basic-addon2" />
                                        <div className="input-group-append">
                                            <span className="input-group-text text-center d-block" onClick={() => applyCouponCode()} id="basic-addon2"> Apply</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            {/* <div v-if="collectedVoucher.length" className="collect_voucher_modal">
                                <h5>Collected Voucher</h5>
                                <div  v-for="(cv,index) in collectedVoucher" :key="index">
                                    <p><input type="hidden" name="collected_voucher" className="collected_voucher_radio" :value="cv.voucher_id" required> 
                                    <img @error="imageLoadError" style="width:100%" :src="baseurl+'/'+cv.voucher.banner" alt="">
                                    </p>
                                </div>
                            </div> */}

                            {/* <div className="voucher_button mt-3">
                                <ul className="list-group list-group-horizontal">
                                    <li v-if="useableVouchers.length" style="width:100%" className="list-group-item">
                                        <a data-toggle="modal" data-target=".use_voucher_modal" href="javascript:void(0)">
                                            <p className="text-center mb-0"> Use Voucher</p>
                                        </a> 
                                        
                                    </li>
                                </ul>
                            </div> */}


                            <div className="paymentmethod mt-3">
                                <b> Payment Method</b>
                                <ul className="list-group list-group-horizontal">
                                    <li data-payment-method="online_payment" className="list-group-item"> 
                                        <p className="text-center mb-0"> <Image width={60} height={60} src="/assets/images/onlinepay.png" alt="" /> <br/><strong>Online Payment</strong></p>
                                    </li>
                                    <li data-payment-method="cash_on_delivery" className="list-group-item selected_payment"> 
                                        <p className="text-center mb-0"> <Image width={60} height={60}src="/assets/images/cash.png" alt="" /> <br/><strong> Cash On Delivery</strong></p>
                                    </li>
                                </ul>
                            </div>
                            <div className="payment-calculation mt-3 mb-4">
                                  <b>Order Summary</b>
                                  <hr/>
                                <ul>
                                    <li  data-subtotal-amount={initcart?.sub_total} className="data_sub_total"> <strong> Sub Total: </strong> <span> BDT {initcart?.sub_total} </span> </li>
                                    <li  data-shipping-cost={initcart?.shipping_cost} className="shipping_cost_li"> <strong>Shipping Cost (+) : </strong> BDT <span className="calculatedShipping">{initcart?.shipping_cost }</span></li>
                                    
                                    {initcart?.packaging_cost > 0 ? (
                                    <li data-packaging-cost-amount={initcart?.packaging_cost} className="data_packaging_cost"> <strong>Packaging Cost(+):</strong> <span> BDT {initcart?.packaging_cost}</span> </li>              
                                    ):('')}
                                    {initcart?.security_charge > 0 ? (
                                    <li data-security-charge-amount={initcart?.security_charge} className="data_security_charge"> <strong> Security Charge (+):</strong>  <span> BDT {initcart?.security_charge}</span> </li>
                                    ):('')}

                                    {initcart?.vat > 0 ? (
                                    <li data-vat-amount={initcart?.vat} className="data_vat"> <strong> Vat (+): </strong>  <span> BDT {initcart?.vat}</span> </li>
                                    ):('')}
                                    
                                    {coupon_discount.status == 1 && Number(coupon_discount.amount) > 0 ? (
                                        <li className="coupon_discount" data-coupon-discount={coupon_discount.amount} > <strong> Coupon Discount(-)<br/>  ({coupon_discount.code}) <a onClick={() => removeCoupon()} className="text-danger" href="#"> Remove </a></strong>  <span>BDT { Number(coupon_discount.amount) }</span></li>
                                    ):(
                                        <li data-coupon-discount="0" className="coupon_discount"  > <strong>Coupon Discount (-) :</strong>  <span>BDT 0</span></li>
                                    )}


                                    <li> <strong className="totaprice" id="totalPrice"  data-total-price={Number(initcart?.shipping_cost) + Number(initcart?.sub_total) } > Total: </strong>  BDT <span className="calculatedTotal">{finalCalculatedTotal}</span></li>
                                </ul>
                            </div>
                            <div className="procced-checkout mt-3">
                                <ul>
                                    <li> <input type="checkbox" value="agree" className="agree" /> <small> I agree to the terms and Conditions</small></li>
                                    <li> 
                                        {userdata?(
                                        <button className="btn btn-primary site_color1 proceed_to_pay" onClick={() => proceedToPay()}> Proceed To Pay </button>
                                        ):(
                                        <a data-bs-toggle="modal" data-bs-target="#popupLogin" className="btn btn-primary site_color1 proceed_to_pay">Proceed To Pay</a>
                                        )}
                                    </li>
                                </ul>
                            </div>


                        </div>
                    </div>
                </div>
            </section>
            ):('')}
          {/* Address Modal start */}
            <div className="modal fade" id="addressModal" tabIndex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div className="modal-dialog modal-dialog-centered modal-lg" role="document">
                  <div className="modal-content">
                  <div className="modal-header border-bottom-0">
                    <button type="button" className="btn-close" data-bs-dismiss="modal" aria-label="Close">
                      {/* <i className="fa fa-times" aria-hidden="true"></i> */}
                    </button>
                  </div>
                  <div className="modal-body">
                      <div className="d-flex flex-column text-center">
                          <ul className="addorselectaddress">
                            <li>  <button className="btn theme-bg-color text-white btn-sm fw-bold mt-lg-0 mt-3"> <i data-feather="check" className="me-2"></i> Select Default Address   </button> </li>
                            <li> OR </li>
                            <li>
                                <button onClick={() => addressShowModal(null, 'add')} className="btn theme-bg-color text-white btn-sm fw-bold mt-lg-0 mt-3"> <i data-feather="plus" className="me-2"></i> Add New Address   </button>
                            </li>
                          </ul>

                          <div className="tab-content">
                              <div id="home" className="tab-pane fade in active">
                                      <table className="table">
                                        <thead>
                                            <tr>
                                                <th scope="col"> Full Name </th>
                                                <th scope="col"> Phone</th>
                                                <th scope="col"> Address</th>
                                                <th scope="col">Set Defalut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                        {allAddress?.address?.map((address, index) => (
                                        <tr key={index} >
                                            <td> { address?.shipping_first_name }  { address?.shipping_last_name }  </td>
                                            <td> { address?.shipping_phone } </td>
                                            <td>{ address?.division?.title }, { address?.district?.title }, { address?.upazila?.title }, { address?.union?.title } <br/> { address?.shipping_address }</td>
                                            <td> 
                                            {allAddress.user.default_address_id == address.id? (
                                                  <div className="select_address" title="It is your default address"> </div>
                                              ):(
                                                  <div className="unselect_address" title="Make this address default" onClick={() => chnageDefultAdress(address.id)}> </div>
                                              )}
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
          {/* Address modal end */}
        </>
      )}
      
    </div>



















    <p data-bs-toggle="modal" data-bs-target="#addressEidt" className='addressEidtModal'></p>
    
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


export default CartPage;



import FrontendLayouts from '@/components/Layouts/Frontend/FrontendLayouts'
import Head from 'next/head'
import Image from 'next/image'
import Meta from "@/components/Meta";
import { useRouter } from 'next/router'
import { useEffect, useState, React, useRef } from 'react';
import axios from 'axios';
import { getSession, useSession } from 'next-auth/react';
import Link from 'next/link';
import Action from "@/components/Buttons/Action";
import AddToCartBtn from "@/components/Buttons/AddToCartBtn";
import Input from '@/components/Input';
import SimillarProduct from "@/components/Layouts/Frontend/Slider/SimillarProduct";
import { ToastContainer, toast } from 'react-toastify';
import { useDispatch } from 'react-redux';
import { AddToCart, InitCart } from '../../lib/features/cart/cartSlice';

export default function  Product({allAddress}) {
const router = useRouter();
const dispatch = useDispatch();
const slug = router.query.slug;
const { data:userdata, status, update, session }= useSession();


const [productfound, setproductfound] = useState(null);
const [singleProduct, setsingleProduct] = useState(null);
const [gallery_images, setgallery_images] = useState(null);
const [ecodedUrl, setecodedUrl] = useState(null);
const [veryfied, setveryfied] = useState(null);
const [seller_ratings_info, setseller_ratings_info] = useState(null);
const [randomnumber, setrandomnumber] = useState(null);
const [calculated_in_stock, setCalculated_in_stock] = useState(null);
const [default_image, setDefault_image] = useState(null);
//const [discount_Percentage, setdiscount_Percentage] = useState(null);

const [calculated_price, setcalculated_price] = useState(null);



const single_product_load = async () => {
    let token = localStorage.getItem("token");
    try {
        const response = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/product/`+slug);
        if(response.data.status == 0 || response.data.status == '0'){
            setproductfound(0);
        }else{
            setproductfound(1);
            setsingleProduct(response.data);
            // this.purchased  = response.data.purchased ?? false;
            setseller_ratings_info(response.data.seller_ratings_info);
            setveryfied(response.data.shop_verified.if_veryfied);
            // let productId = response.data.id;
            // this.productId = productId;
            // that.getComments(productId);
            // //console.log('Single product meta = '+response.data.meta);
            setCalculated_in_stock(response.data.calculated_in_stock);
            // this.produt_id      = response.data.id;
            setDefault_image(response.data.default_image);
            //setdiscount_Percentage(null);
            if(response.data.gallery_images){
                setgallery_images(response.data.gallery_images.split(","));
            }else{
                setgallery_images(null);
            }
            // let user_id = localStorage.getItem("userID");
            // let categoryId = response.data.category_id;
            setcalculated_price(response.data.price_after_offer);
            // //Check compare
            // axios.post(this.$baseUrl+'/api/v1/check-compare', {product_id:productId}, axiosConfig).then(response => {
            //     this.checkCompare = response.data;
            // });
            // //Check reviewed
            // axios.post(this.$baseUrl+'/api/v1/check-reviewed', {user_id:user_id, product_id:productId}).then(response => {
            //     this.checkCommented = response.data.message;
            // });
            // //All star ratig
            // axios.get(this.$baseUrl + "/api/v1/get-all-rating/"+productId).then((response) => {
            //     this.AllRating = response.data;
            // });
        }
    } catch (error) {
        console.log('error ', error);
    }
}





const simpleAddToCart = async (product_id) => {
    try {
        const token = localStorage.getItem('token');
        const session_key = localStorage.getItem("session_key");
        const qty = $('.qty').attr('data-qty');
        let formData = new FormData();
        formData.append('product_id', product_id);
        formData.append('qty', qty);
        formData.append('session_key', session_key);
        const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/add-to-cart`, formData, {
            headers: {
                Authorization: `Bearer ${token}`,
            }
        });

        if(response.data.status == 1){
            await dispatch(InitCart());
            toast.success('Added to cart successfully !', {
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
            toast.error(response.data.message, {
                position: "bottom-right",
                autoClose: 5000,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: false,
                progress: undefined,
                theme: "light",
            });
        }
    }catch(error) {
        toast.error(response.data.message, {
            position: "Something went wrong",
            autoClose: 5000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: false,
            progress: undefined,
            theme: "light",
        });
    }
}

const variable_form = useRef(null);
const variableAddToCart = async (e) => {
    e.preventDefault();
    try {
        const token = localStorage.getItem('token');
        const session_key = localStorage.getItem("session_key");

        const formData = new FormData(variable_form.current);
        const data = {session_key:session_key};

        for(let [key, val] of formData.entries()) {
            Object.assign(data, { [key]: val });
        }
        const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/variable-add-to-cart`, data, {
            headers: {
                Authorization: `Bearer ${token}`,
            }
        });
        if(response.data.status == 1){
            await dispatch(InitCart());
            toast.success('Added to cart successfully !', {
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
            toast.error(response.data.message, {
                position: "bottom-right",
                autoClose: 5000,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: false,
                progress: undefined,
                theme: "light",
            });
        }
    }catch(error) {
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





const digitaladdToCart = async (product_id, product_type=null) => {
    try {
        const token = localStorage.getItem('token');

        const session_key = localStorage.getItem("session_key");
        const qty = $('.digital_product_qty').attr('data-qty');
        let phone_number = $('.phone_number').val();
        let service_date = $('#service_date').val();
        let service_time = $('#service_time').find('option:selected').val();
        if(product_type == 'service'){
            if(service_date == '' || service_time == '' ){
                toast.error('Service date and time is required!.', {
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
            }
        }

        if(product_type == 'digital'){
            if(phone_number.length != 11){
                toast.error('Please type a valid mobile number.', {
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
            }
        }


        let formData = new FormData();
        formData.append('shipping_option', '');
        formData.append('service_date', service_date);
        formData.append('service_time', service_time);
        formData.append('phone_number', phone_number);
        formData.append('product_id', product_id);
        formData.append('qty', qty);
        formData.append('session_key', session_key);
        const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/digital-add-to-cart`, formData, {
            headers: {
                Authorization: `Bearer ${token}`,
            }
        });
        if(response.data.status == 1){
            await dispatch(InitCart());
            toast.success('Added to cart successfully !', {
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
            toast.error(response.data.message, {
                position: "bottom-right",
                autoClose: 5000,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: false,
                progress: undefined,
                theme: "light",
            });
        }
    }catch(error) {
        toast.error(response.data.message, {
            position: "Something went wrong",
            autoClose: 5000,
            hideProgressBar: false,
            closeOnClick: true,
            pauseOnHover: true,
            draggable: false,
            progress: undefined,
            theme: "light",
        });
    }
}

useEffect(() => {
    if (slug) {
        single_product_load();
        setrandomnumber(Math.floor(Math.random() * (1000 - 1 + 1)) + 1);
        setTimeout(function(){
            $('.show-small-img').first().trigger('click');
        },500);
        if (typeof window !== 'undefined') {
            const domainName = window.location.hostname;
            setecodedUrl(`${process.env.APP_FRONTEND}`+router.asPath);
          }
    }
}, [slug]); 

return (
<FrontendLayouts>
<Meta title= {`${'Product'} | Dhroobo`} keywords="dhroobo keywords" decription="dhroobo decription" />
<div className="singleproductpage">
{/* <div dangerouslySetInnerHTML={ __html: content?.content?.description } /> */}

    {productfound > 0 ? (
    <section id="product-details">
        <div className="container custom_container">
            <div className="row">
                <div className="col-md-12">
                    <div className="bredcum">
                        <p> { singleProduct?.breadcrumb } </p>
                    </div>
                </div>
            </div>
            <div className="row details-container">
                <div className="col-md-9 image-section">
                    <div className="row">
                        <div className="col-md-6 p-0">
                            <div className="mycontainer">
                               {singleProduct.video_link ? (
                                <div id="show-video">
                                    <iframe width="100%" height="400" src="singleProduct.video_link"></iframe>
                                </div>
                                ):('')}
                                <div className="zoom-show">
                                    <img src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${singleProduct.default_image}`} id="show-img"   alt="" />
                                </div>
                                <div className="small-img" >
                                    <img  src={`/images/right.png`} className="icon-left" id="prev-img" width={30}  height={100}  alt="" />
                                    <div className="small-container">
                                        <div id="small-img-roll">
                                            <img src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${singleProduct.default_image}`} className="show-small-img"   alt="" />
                                            {gallery_images?.map((img, index) => (
                                            <img key={'z'+index} src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${img}`} className="show-small-img"   alt="" />
                                            ))}
                                            {singleProduct.video_link ? (
                                            <img src={`/images/video.jpg`} className="show-small-img video"   alt="" />
                                            ):('')}
                                        </div>
                                    </div>
                                   
                                    <img src={`/images/right.png`} className="icon-right" id="next-img" width={30}  height={30}  alt="" /> 
                                </div>
                            </div>
                        </div>
                        <div className="col-md-6 description-section">
                            <h4 className="mb-0">{ singleProduct.title }</h4>

                            


                            {singleProduct.weight ? (
                            <small> <b className="single_page_weight">weight: </b> { singleProduct?.weight } <span> {singleProduct?.weight_unit} </span> <br/> </small>
                            ):('')}
                            {singleProduct.vat > 0 ? (
                            <small className="text-danger">{ singleProduct.vat }% VAT Applicable <br/></small>
                            ):('')}    
                            <small className="text-secondary"> <b>SKU: </b> <span className="single_page_sku"> <b>{ singleProduct?.sku }</b> </span>  </small>
                            <p><small>{ singleProduct.short_description }</small></p>
    
                            {singleProduct.minimum_cart_value > 0 ? (
                            <p className="text-danger"><small>Note: You need minimum BDT { singleProduct?.minimum_cart_value } in your cart to buy this item.</small></p>
                            ):('')}  
                            <div className='star-rating title_rating'>
                                <Action data={singleProduct}></Action>
                            </div>
                            <div className="share_section">	
                            <ul className="share_list">
                                <li>
                                    <div className="share_parent"> 
                                        <i className="fa fa-share-alt" aria-hidden="true"></i> 
                                        <div className="share_box">
                                            <ul>
                                                <li> <a href={`https://www.facebook.com/sharer/sharer.php?u=`+ecodedUrl} target="_blank"> <i className="fa fa-facebook" aria-hidden="true"></i> <span>Share on Facebook</span>  </a> </li>
                                                <li> <a href={`https://twitter.com/share?url=`+ecodedUrl}> <i className="fa fa-twitter" aria-hidden="true"></i>  <span> Share on Twitter  </span></a> </li>
                                                <li> <a href={`whatsapp://send?text=`+ecodedUrl} data-action="share/whatsapp/share" onClick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on whatsapp"> <i  className="fa fa-whatsapp" aria-hidden="true"></i> <span>Share on Whatsapp  </span></a> </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            </div>
                            <br/>




                            {/* Simple product start */}
                            {singleProduct.product_type == 'simple' ? (
                            <>
                            <div className="single-page-price">
                                <div className="currect-price"><b>BDT {  singleProduct.price_after_offer }</b></div>
                                {singleProduct.special_price_type == 1 && Number(singleProduct.price_after_offer) <  Number(singleProduct.price)  ? (
                                    <div className="single-old-price">
                                        <del>BDT { singleProduct.price }</del>
                                    </div>
                                ):('')}
                                {singleProduct.special_price_type == 2 && Number(singleProduct.price_after_offer) <  Number(singleProduct.price)  ? (
                                    <div className="single-old-price">
                                        <del>BDT { singleProduct.price }</del> 
                                        <br/>  <small>Discount: </small> <small>  { singleProduct?.discount_Percentage } %</small></div>
                                ):('')}
                            </div>

                            <div className="row">
                                {Number(singleProduct.in_stock) > 0 && Number(singleProduct.qty) > 0 ? (
                                <div className="col-md-12 quantity_grid">
                                    <ul className="quantity">
                                        <li>Quantity:</li>
                                    </ul>
                                    <ul className="quantity_calculate">
                                        <li><button className="minus">-</button></li>
                                        <li><button className="qty" data-qty='1' data-total_qty={singleProduct.max_cart_qty} data-productId={singleProduct.id}>1</button></li>
                                        <li><button className="plus">+</button></li>
                                    </ul>
                                </div>
                                ):('')}
                            </div>
                            {Number(singleProduct.in_stock) > 0 && Number(singleProduct.qty) > 0 ? (
                            <div className="row add_buy">
                                <div className="col-md-12">
                                {/* <AddToCartBtn className="btn btn-animation text-light btn-sm mx-auto mt-sm-3 mt-2"  data={singleProduct}></AddToCartBtn> */}
                                    <button className={'btn btn-animation text-light btn-md mx-auto mt-sm-3 mt-2 disabledbtn2'+singleProduct.id} onClick={() => simpleAddToCart(singleProduct.id)}> Add To Cart</button>
                                </div>
                            </div>
                            ):(
                            <div className="row out_of_stock_single_page">
                                <div className="col-12 col-lg-12">
                                    <div className="out_of_stock"> Out Of Stock </div>
                                </div>
                                <div className="col-12 col-lg-12">
                                    <div className="'restockbtn btn btn-primary disabledbtn'+singleProduct.id"  onClick="restockRequest(singleProduct.id, singleProduct.seller_id)"> <i className="fa fa-bicycle mr-2" aria-hidden="true"></i> Restock Request</div>
                                </div>
                            </div>
                            )}
                            </>
                            ):('')}
                            {/* Simple product end */}









                            {/* variable product start */}
                            {singleProduct.product_type == 'variable' ? (
                            <div id="variable_product">
                                <form ref={variable_form} id="variable_form" className="mb-5" onSubmit={variableAddToCart}>
                                    <div className="single-page-price">
                                        <div className="calculated_price" data-calculated-price={Number(calculated_price)}><b>BDT <span className="price_text">{ Number(calculated_price) }</span></b></div>
                                        {Number(calculated_price) < Number(singleProduct.price) ? (
                                        <div className="single-old-price">
                                            <del>BDT  { singleProduct.price }</del>
                                            {singleProduct?.discount_Percentage > 0 ? (
                                            <span className="discout_percentage">{ singleProduct?.discount_Percentage }%</span>
                                            ):('')}
                                        </div>
                                        ):('')}
                                        <Input type="hidden" className="variable_sku" name="variable_sku" />
                                    </div>
                                    <div className="row">
                                        <div className="col-md-12 productSize">
                                        {singleProduct?.meta.map((metas, index) => (
                                            <span key={'A'+index}>
                                                {metas.meta_key == 'custom_options' ? (
                                                <span>
                                                    {metas.meta_value.map((val, index) => (
                                                    <span key={'B'+index}>
                                                        {singleProduct.hidden_option[val.title] ? (
                                                        <div className="row">
                                                            {/*Radio start*/}
                                                            {val.type == 'radio' ? (
                                                            <div className="col-md-12 col-lg-12 mb-3 option_parent_group">
                                                                    <div className="variant_title"> 
                                                                        <b> { val.title } 
                                                                            {val.is_required == '1' ? ( <b className="is_required">* :</b>  ):('')}
                                                                        </b>
                                                                        <span className="selectedValue"></span>
                                                                        <Input type="hidden" data-additional-price="0" data-additional-qty="-1" className="variant_input" name={val.title} />
                                                                    </div>
                                                                    <ul>
                                                                        {val?.value?.map((option, index) => (
                                                                        <li key={'C'+index}> 
                                                                            {option.qty > 0 ? (
                                                                            <div className="radio_image">
                                                                                {option.variant_image ? (
                                                                                <span>
                                                                                    <img  title={'Additional Price BDT '+ option.price} className="color_image option_selector" src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${option.variant_image}`} data-price="option.price" 
                                                                                    data-variable-sku={option.sku} 
                                                                                    data-variable-qty={option.qty} 
                                                                                    data-title={option.title} 
                                                                                    data-sku={option.sku}/>
                                                                                </span>
                                                                                ):(
                                                                                <span 
                                                                                    data-price={option.price}
                                                                                    data-variable-sku={option.sku} 
                                                                                    data-variable-qty={option.qty}  
                                                                                    data-title={option.title}
                                                                                    title={'Additional Price BDT '+ option.price} 
                                                                                    className="option_selector radio_select">
                                                                                    { option.title }  <span className="radio_select_price"> (+BDT { option.price }) </span>
                                                                                </span>
                                                                                )}
                                                                            </div>
                                                                            ):('')}
                                                                        </li>
                                                                        ))}
                                                                    </ul>
                                                            </div>
                                                            ):('')}
                                                            {/*Radio End*/}

                                                            {/*Dropdown start */}
                                                            {val.type == 'dropdown' ? (
                                                            <div className="col-md-12 col-lg-12 mb-3 option_parent_group">
                                                                <div className="variant_title"> 
                                                                    <b>{ val.title }: </b> 
                                                                    <span className="selectedValue"></span>
                                                                    <Input type="hidden" data-additional-price="0" data-additional-qty="-1" className="variant_input" name={val.title} />
                                                                </div>
                                                                <select className="form-control variant_dropdwon">
                                                                    <option value="0" disabled selected> -Select {val.title}- </option>
                                                                    {val?.value?.map((option, index) => (
                                                                        <>
                                                                            {option.qty > 0 ? (
                                                                            <option
                                                                                value={option.title} 
                                                                                data-variable-qty={option.qty}
                                                                                data-variable-sku={option.sku}
                                                                                data-dropdownprice={option.price}
                                                                                key={'D'+index}>
                                                                                { option.title+' (+BDT '+option.price+')' } 
                                                                            </option>
                                                                            ):('')}
                                                                        </>
                                                                    ))}
                                                                </select>
                                                            </div>
                                                            ):('')}
                                                            {/*Dropdown End */}
                                                        </div>
                                                        ):('')}
                                                    </span>
                                                    ))}
                                                </span>
                                                ):('')}
                                            </span>
                                        ))}
                                        </div>
                                        {/*Quantity start*/}
                                        {Number(calculated_in_stock) > 0  ? (
                                        <div className="col-md-12 quantity_grid">
                                            <ul className="quantity">
                                                <li>Quantity:</li>
                                            </ul>
                                            <ul className="quantity_calculate">
                                                <li><button type="button" className="minus">-</button></li>
                                                <li>
                                                    <button type="button" className="qty" data-qty='1' data-total_qty={singleProduct.max_cart_qty}>1</button>
                                                    <Input type="hidden" name="qty" className="qtyInput" value="1" />
                                                </li>
                                                <li><button type="button" className="variable_plus">+</button></li>
                                            </ul>
                                        </div>
                                        ):(
                                        <div className="row out_of_stock_single_page out_of_stock_digi_vari">
                                            <div className="col-12 col-lg-12">
                                                <div className="out_of_stock"> Out Of Stock </div>
                                            </div>
                                            <div className="col-12 col-lg-12">
                                                <div className={'restockbtn btn btn-primary disabledbtn'+singleProduct.id} onClick={() => restockRequest(singleProduct.id, singleProduct.seller_id)}> <i className="fa fa-bicycle mr-2" aria-hidden="true"></i> Restock Request</div>
                                            </div>
                                        </div>
                                        )}
                                        {/*Quantity End*/}
                                    </div>
                                    {Number(calculated_in_stock) > 0  ? (
                                    <div className="row add_buy add_buy_variable">
                                        <div className="col-md-6">
                                            <Input type="hidden" name="product_id" value={singleProduct.id} />
                                            <button type="submit" className={'btn btn-primary mt-3 variable_disabled_btn addToCart disabledbtn'+singleProduct.id} disabled> Add To Cart</button>
                                        </div>
                                    </div>
                                    ):('')}
                                </form>
                            </div>
                            ):('')}
                            {/* variable product end */}




















	
                            {/* Digital nad Service product start */}
                            {singleProduct.product_type == 'digital' || singleProduct.product_type == 'service' ? (
                            <span className="digital_product">
                                <div className="single-page-price">
                                    <div className="currect-price"><b>BDT { calculated_price }</b></div>
                                    {singleProduct.special_price_type == 1 ? (
                                    <span>
                                        {Number(calculated_price) < Number(singleProduct.price) ? (
                                        <div className="single-old-price">
                                            <del>BDT {singleProduct.price }</del>
                                        </div>
                                        ):('')}
                                    </span>
                                    ):('')}

                                    {singleProduct.special_price_type == 2 ? (
                                    <span>
                                        {Number(calculated_price) < Number(singleProduct.price) ? (
                                        <div className="single-old-price">
                                            <del>BDT {singleProduct.price }</del> <br/>  
                                            <small>Discount: </small>
                                            <small> {discount_Percentage }%</small>
                                        </div>
                                        ):('')}
                                    </span>
                                    ):('')}
                                </div>

                                {singleProduct.product_type == 'digital' ? (
                                <span>
                                    {singleProduct?.meta.map((meta, index) => (
                                    <span key={'E'+index}>
                                        {meta.meta_key == 'product_sale_option' ? (
                                        <span>
                                            {meta.meta_value == 'digital' ? (
                                            <span>
                                                    <div className="row">
                                                        <div className="col-md-12">
                                                            <div className="form-group">
                                                                <label for="">Mobile number</label>
                                                                <Input type="text" name="number" placeholder="Mobile number" className="form-control phone_number" />
                                                            </div>
                                                        </div>
                                                    </div>
                                            </span>
                                            ):('')}
                                        </span>
                                        ):(
                                        <span>
                                            <Input type="hidden" name="number" value="-1" className="form-control phone_number" />
                                        </span>
                                        )}
                                    </span>
                                    ))}
                                </span>



                                ):(



                                <span>
                                    <label for="">When do you want to take service from us?</label>
                                    <p className="mb-0">Select your prefer date <span className="text-danger">*</span> </p>
                                    <Input type="date" name="service_date" id="service_date" className="form-control" />
                                    <Input type="hidden" name="number" value="-1" className="form-control phone_number" />
                                    <p className="mb-0 mt-2">Select your prefer time, expert will arrive by your selected time <span className="text-danger">*</span></p>
                                    <select name="service_time" id="service_time" className="form-control">
                                        <option value="10-11am">10-11 am</option>
                                        <option value="11-12pm">11-12 pm</option>
                                        <option value="12-1pm">12-1 pm</option>
                                        <option value="1-2pm">1-2 pm</option>
                                        <option value="2-3pm">2-3 pm</option>
                                        <option value="3-4pm">3-4 pm</option>
                                        <option value="4-5pm">4-5 pm</option>
                                        <option value="5-6pm">5-6 pm</option>
                                        <option value="6-7pm">6-7 pm</option>
                                        <option value="7-8pm">7-8 pm</option>
                                    </select>
                                </span>
                                )}

                                <div className="row">

                                    {singleProduct.in_stock > 0 && singleProduct.qty > 0 ? (
                                    <div className="col-md-12 quantity_grid">
                                        <ul className="quantity">
                                            <li>Quantity:</li>
                                        </ul>
                                        <ul className="quantity_calculate">
                                            <li><button className="minus">-</button></li>
                                            <li><button className="qty digital_product_qty" data-qty='1' data-total_qty={singleProduct.qty} data-productId={singleProduct.id}>1</button></li>
                                            <li><button className="plus">+</button></li>
                                        </ul>
                                    </div>
                                    ):(
                                    <div className="row out_of_stock_single_page out_of_stock_digi_vari">
                                        <div className="col-12 col-lg-12">
                                            <div className="out_of_stock"> Out Of Stock </div>
                                        </div>
                                        <div className="col-12 col-lg-12">
                                            <div className={'restockbtn btn btn-primary disabledbtn'+singleProduct.id} onClick="restockRequest(singleProduct.id, singleProduct.seller_id)"> <i className="fa fa-bicycle mr-2" aria-hidden="true"></i> Restock Request</div>
                                        </div>
                                    </div>
                                    )}
                                </div>
                                {singleProduct.in_stock > 0 && singleProduct.qty > 0 ? (

                                <div className="row add_buy">
                                    {userdata ? (
                                    <div className="col-md-6">
                                        {singleProduct.product_type == 'digital' ? (
                                        <button className={'btn btn-primary mt-3  addToCart disabledbtn'+singleProduct.id}  onClick={() => digitaladdToCart(singleProduct.id, null)}> Add To Cart </button>
                                        ):(
                                        <button className={'btn btn-primary mt-3 addToCart disabledbtn'+singleProduct.id} onClick={() => digitaladdToCart(singleProduct.id, 'service')}>  Add To Cart </button>
                                        )}
                                    </div>
                                    ):(
                                        <div className="col-md-6">
                                            <button className="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#popupLogin"> Add To Cart </button>
                                        </div>
                                    )}
                                </div>
                                
                                ):('')}
                            </span>
                            ):('')}
                            {/* Digital product end */}















                        </div>
                    </div>
                </div>
                <div className='col-md-3 pt-2 delivery'>
                    <div className="row">
                        <div className="col-md-12"><p>Delivery Option</p></div>
                    </div>
                    {allAddress?.address.length  > 0 ? (
                    <div className="row deliveryOption">
                        <div className="col-md-12">
                            {allAddress?.address.map((address, index) => (
                            <div className="row" key={`f`+index} >
                                {userdata?.customer?.default_address_id == address.id ? (
                                    <>
                                        <div className="col-2 col-sm-2 col-md-2"><i className="fa fa-map-marker" aria-hidden="true"></i></div>
                                        <div className="col-10 col-sm-10 col-md-10 p-0" id="dynamicAddress"> 
                                            <span>{address?.division?.title}, </span> 
                                            <span>{address?.district?.title}, </span>
                                            <span>{address?.upazila?.title}, </span>
                                            <span>{address?.union?.title}, </span>
                                            <span>{address?.shipping_address}</span>
                                        </div>
                                    </>
                                ):('')}
                            </div>
                            ))}
                        </div>
                    </div>
                    ):('')}
                    
                    {allAddress?.address.length  > 0 ? (
                    <div>
                        {singleProduct?.product_type != 'digital' ? (
                            <span>
                            {singleProduct?.shipping_options?.map((sp, index) => (
                                <div className="row deliveryOption cashOndelivery" key={'K'+index}>
                                    {sp !== null ? (
                                    <>
                                    <div className="col-1 col-sm-1 col-md-1"><i className="fa fa-truck" aria-hidden="true"></i></div>
                                    <div className="col-8 col-sm-8 col-md-8">
                                        <span className="slectedShipping"> <span className="selectedShippingTitle text-capitalize">{index.replace(/[#_]/g,' ')}</span> </span> 
                                        {index == 'free_shipping' ? (<p><small className="selectedShippingSubtitle">Package will be send within 7 to 15 days</small></p> ):('')}
                                        {index == 'standard_shipping' ? (<p><small className="selectedShippingSubtitle">Package will be send within 4 to 7 days</small></p> ):('')}
                                        {index == 'express_shipping' ? (<p><small className="selectedShippingSubtitle">Package will be send within 1 to 3 days</small></p> ):('')}
                                    </div>
                                    <div className="col-3 col-sm-3 col-md-3 text-right">
                                        {index == 'free_shipping' ? (
                                        <span> BDT 0</span>
                                        ):(
                                        <span className="standardShippingConst">
                                            {sp != 0 ? (
                                                <span>BDT {sp}</span>
                                            ):(
                                                <span className="text-danger">Not Available</span>
                                            )}
                                        </span>
                                        )}
                                    </div>
                                    </>
                                    ):('')}
                                </div>
                            ))}
                            </span>
                        ):('')}
                    </div>
                    ):('')}
                    
                    {singleProduct?.meta != '' ? (
                    <span>
                        {singleProduct?.meta.map((metas, index) => (
                        <span key={'L'+index}>
                            {metas.meta_key == 'product_miscellaneous_information' ? (
                            <span>
                                {metas.meta_value.allow_cash_on_delivery ? (
                                <span>
                                    <div className="row deliveryOption cashOndelivery">
                                        <div className="col-2 col-sm-2 col-md-2"><i className="fa fa-money" aria-hidden="true"></i></div>
                                        <div className="col-10 col-sm-10 col-md-10 p-0">
                                            {metas.meta_value.allow_cash_on_delivery == 'on' ? (
                                            <span > Cash On Delivery Available</span>
                                            ):(
                                            <span> Cash On Delivery Not Available</span>
                                            )}
                                        </div>
                                    </div>
                                </span>
                                ):('')}
                            </span>
                            ):('')}
                        </span>
                        ))}
                    </span>
                    ):('')}

                    {singleProduct?.meta != '' ? (
                    <span>
                        {singleProduct?.meta.map((metas, index) => (
                        <span key={'M'+index}>
                            {metas.meta_key == 'product_miscellaneous_information' ? (
                            <span>
                                {metas.meta_value.allow_change_of_mind ? (
                                <span>
                                    <div className="row deliveryOption returnAndWarranty">
                                        <div className="col-12 col-sm-12 col-md-12"><p>Return & Warranty</p></div>
                                        <div className="col-1 col-sm-1 col-md-1"><i className="fa fa-refresh" aria-hidden="true"></i></div>
                                        <div className="col-11 col-sm-11 col-md-11">
                                            7 Days Return <br />
                                            {metas.meta_value.allow_change_of_mind == 'on' ? (
                                            <span> <small>Change of mind is applicable</small></span>
                                            ):(
                                                <span> <small>Change of mind is not applicable</small> </span>
                                            )}
                                        </div>
                                    </div>
                                </span>
                                ):('')}
                            </span>
                            ):('')}
                        </span>
                        ))}
                    </span>
                    ):('')}

                    {singleProduct?.meta != '' ? (
                    <span>
                        {singleProduct?.meta.map((metas, index) => (
                        <span key={'M'+index}>
                            {metas.meta_key == 'product_miscellaneous_information' ? (
                            <span>
                                {metas.meta_value.warrenty_period ? (
                                <span>
                                    <div className="row deliveryOption returnAndWarranty">
                                        <div className="col-1 col-sm-1 col-md-1">
                                            {metas.meta_value.warrenty_period > 0 ? (
                                            <i className="fa fa-check" aria-hidden="true"></i>
                                            ):(
                                                <i className="fa fa-times" aria-hidden="true"></i>
                                            )}
                                        </div>
                                        <div className="col-11 col-sm-11 col-md-11">
                                            {metas.meta_value.warrenty_period > 0 ? (
                                                <span> Warranty Available </span>
                                            ):(
                                                <span>Warranty Not Available</span>
                                            )}
                                        </div>
                                    </div>
                                </span>
                                ):('')}
                                </span>
                            ):('')}
                        </span>
                        ))}
                    </span>
                    ):('')}

                    <div className="row deliveryOption returnAndWarranty">
                        <div className="col-6 col-sm-6 col-md-8">
                            <p className="m-0"> Shop Name </p>
                            <h6> <Link href={'/shop/'+`${singleProduct?.shop_slug}`}>{ singleProduct?.shop_name}</Link> </h6>
                            {veryfied ? (
                            <div className="flagship_relative"><span> <img src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${singleProduct?.shop_verified?.veryfied_banner}`} alt="Flagship" /> </span></div>
                            ):('')}
                        </div>
                    </div>
                    <div className="row rating text-center">
                        <div className="col-5 col-sm-5 col-md-5">
                            <small>Seller Ratings</small>
                            <br />
                            {seller_ratings_info?.seller_ratings ? (
                                <b>{ seller_ratings_info.seller_ratings }%</b>
                            ):(
                                <b>0%</b>
                            )}
                        </div>
                        <div className="col-7 col-sm-7 col-md-7">
                            <small>Shipped on Time</small>
                            <br />
                            {seller_ratings_info?.shipped_on_time ? (
                                <b>{ seller_ratings_info?.shipped_on_time }%</b>
                            ):(
                                <b>0%</b>
                            )}
                        </div>
                    </div>
                    <div className="row rating text-center go-to-store">
                        <div className="col-md-12">
                            <span>  <Link href={'/shop/'+`${singleProduct?.shop_slug}`}> Go To Store </Link> </span>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </section>
    ):(
        <div className="container custom_container">
            <div className="row promotion_page">
                <div className="col-md-12">
                    <h4> Product Not Found  </h4> 
                    <p> <Link href="/product" className="Link-active"> Browse Product</Link></p>
                </div>
            </div>
        </div>
    )}
    <section id="product-details-description">
        <div className="container custom_container">
            <div className="col-md-12 product-details-descripiton">
                <div className="row">
                    <div className="col-md-12 product-section-box">
                        <ul className="nav nav-tabs custom-nav" id="myTab" role="tablist">
                            <li className="nav-item active" role="presentation">
                                <button className="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true"> Product Description </button>
                            </li>
                            <li className="nav-item" role="presentation">
                                <button className="nav-link" data-toggle="specification-tab" href="#specification" aria-controls="specification" data-bs-toggle="tab" data-bs-target="#specification" type="button" role="tab" aria-selected="true"> Specification</button>
                            </li>
                            {/* <li className="nav-item" role="presentation">
                                <button className="nav-link" data-toggle="rating-tab" href="#rating" aria-controls="rating" data-bs-toggle="tab" data-bs-target="#rating" type="button" role="tab" aria-selected="true"> Ratings & Reviews</button>
                            </li> */}

                            {singleProduct?.meta.map((metas, index) => (
                            <span key={'O'+index}> 
                            {metas.meta_key == 'tab_option' ? (
                            <span>
                            {metas.meta_value.map((val, index) => (    
                            <span key={'P'+index}> 
                                <li className="nav-item" role="presentation">
                                    <a className="nav-link" data-toggle={'dynamic_tab'+index} href={'#dynamic_tab'+index} aria-controls={'dynamic_tab'+index} data-bs-toggle="tab" data-bs-target={'#dynamic_tab'+index} type="button" role="tab" aria-selected="true"> { val.tab_option_title }</a>
                                </li>
                            </span>
                            ))}
                            </span>
                            ):('')}
                            </span>
                            ))}



                        </ul>
                        <div className="tab-content custom-tab" id="myTabContent">
                            <div className="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                                {/* <div dangerouslySetInnerHTML={ __html: singleProduct?.description }></div> */}
                            </div>

                            {singleProduct?.specification ? (
                            <div className="tab-pane fade" id="specification" role="tabpanel" aria-labelledby="specification">
                                {singleProduct?.specification.map((sp, index) => (
                                <div key={'Q'+index}>
                                    {sp.meta_value ? (
                                    <p> 
                                        <strong className="text-capitalize">{ sp.meta_key.replace(/_/g, " ") }</strong> : {sp.meta_value} 
                                    </p>
                                    ):('')}
                                </div>
                                ))}
                            </div>
                            ):('')}
                            {/* <div className="tab-pane fade show" id="rating" role="tabpanel" aria-labelledby="rating-tab">
                                Ratings should be dynmice here
                            </div> */}

                            {singleProduct?.meta.map((metas, index) => (
                            <span key={index}> 
                                {metas.meta_key == 'tab_option' ? (
                                    <span>
                                        {metas.meta_value.map((val, index) => (
                                            <span key={index}> 
                                                <div className="tab-pane fade" id={'dynamic_tab'+index} role="tabpanel" aria-labelledby={'dynamic_tab'+index}>
                                                    <p>{ val.tab_option_description }</p>
                                                </div>
                                            </span>
                                        ))}
                                    </span>
                                ):('')}
                            </span>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section className="products_shop margin_bottom_custom">
        <div className="product-main">
            <div className="container custom_container">
            <div className="row">
                <div className="col-md-12">
                <div className="row">
                <div className="col-md-6 col-lg-6">
                    <div className="title">
                        <h2> Simillar Products</h2>
                    </div>
                </div>
                </div>

                <div className="category-slider-2 product-wrapper no-arrow">
                    <SimillarProduct  id={singleProduct?.id} categoryId={singleProduct?.category_id} />
                </div>
                    
                </div>
            </div>
            </div>
        </div>
    </section>
</div>
</FrontendLayouts>
)
}





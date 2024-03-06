
"use client"
import { useEffect, useState } from 'react'
import { useDispatch } from 'react-redux';
import { AddToCart, InitCart } from '../lib/features/cart/cartSlice';
import { getSession, useSession,  } from 'next-auth/react';
import axios from 'axios';
import Link from 'next/link';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Image from 'next/image'
import Action from "@/components/Buttons/Action";
import AddToCartBtn from "@/components/Buttons/AddToCartBtn";



const SingleProduct = (props) => {
    const { data } = props;
    const dispatch = useDispatch();
    const [loading, setLoading] = useState(false);
    const handleAddToCart = async (data) => {
      setLoading(true);
      try {
        await dispatch(AddToCart(data));
        await dispatch(InitCart());
        setLoading(false);
      } catch (error) {
        console.error('Failed to add item to cart', error);
        setLoading(false);
      }
    }



    return (
        <>
            <div className="product-box mb-2">
                <div className="product-image">
                    <Link href={'/product/'+`${data?.slug}`}>
                        <Image src={`${process.env.NEXT_PUBLIC_BACKEND_URL}/${data.default_image}`}
                            className="img-fluid blur-up lazyload"  width={180} height={140} alt=""/>
                    </Link>
                    <Action data={data}></Action>
                </div>
                <div className="product-detail">
                    <Link href={'/product/'+`${data?.slug}`}>
                        <h6 className="name">{ data.title }</h6>
                    </Link>

                    <h5 className="sold text-content">
                        <span className="theme-color price">BDT { data.price_after_offer }</span>
                        {data.price_after_offer < data.price ? (
                        <del>BDT { data.price}</del>
                        ):('')}
                    </h5>
                    <div className="add-to-cart-box mt-2">
                            {data?.product_type == 'variable' || data?.product_type == 'digital' || data?.product_type == 'service'  ? (
                                <Link href={'/product/'+`${data?.slug}`}><button className="btn btn-add-cart addcart-button" tabIndex="0">Details </button></Link>
                            ):(
                            <>
                                {data?.in_stock > 0 && data?.qty > 0 ? (
                                    <AddToCartBtn  data={data}></AddToCartBtn>
                                ):(
                                    <button className="btn btn-sm mb-2 out_of_stock"> Out Of Stock </button>
                                )}
                            </>
                        )}
                    </div>
                </div>
            </div>
        </>
    )
}

export default SingleProduct;

"use client"

import { useEffect, useState } from 'react'
import { useDispatch } from 'react-redux';
import { AddToCart, InitCart } from '../../lib/features/cart/cartSlice';
import { getSession, useSession,  } from 'next-auth/react';
import axios from 'axios';
import Link from 'next/link';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

const AddToCartBtn = (props) => {
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
        <div> 
        <button className="btn btn-add-cart addcart-button" tabIndex="0" onClick={() => handleAddToCart(data)} >Add To Cart</button>
        
        </div>
    )
}

export default AddToCartBtn;
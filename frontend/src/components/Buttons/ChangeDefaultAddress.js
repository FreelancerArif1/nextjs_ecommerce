
"use client"

import { useEffect, useState } from 'react'
import { useDispatch } from 'react-redux';
import { removeItem, InitCart } from '../../lib/features/cart/cartSlice';
import { getSession, useSession,  } from 'next-auth/react';
import axios from 'axios';
import Link from 'next/link';


const ChangeDefaultAddress = (props) => {
    const { data } = props;
console.log('data 1 ', data);
    const dispatch = useDispatch();
    const [loading, setLoading] = useState(false);


    const chnageDefultAdress = async (data) => {
        console.log('data 2 ', data);


        const token = localStorage.getItem('token');
        try {
            let formData = new FormData();
            formData.append('data', data);
            formData.append('pickpoint', 0);
            const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/update-default-address/`, formData,{
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            if (response.data.status == 0) {
                console.log('error =',response.data.message);
            }else if(response.data.status == 1){
                getAllAddress();
                console.log('success =',response.data.message);
            }
        } catch (error) {
            console.log('error ', error);
        }
    }    


    return (
        <> 
            <div className="unselect_address" title="Make this address default" onClick={() => chnageDefultAdress(data)}> </div>
        </>
    )
}

export default ChangeDefaultAddress;
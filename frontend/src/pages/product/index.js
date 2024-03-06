

import FrontendLayouts from '@/components/Layouts/Frontend/FrontendLayouts'
import Head from 'next/head'
import Image from 'next/image'
import Meta from "@/components/Meta";
import { useRouter } from 'next/router'
import { useEffect, useState, React } from 'react';
import axios from 'axios';
export default function  Product() {
    

    return (
        <FrontendLayouts>
            <Meta title= {`${'Product'} | Dhroobo`} keywords="dhroobo keywords" decription="dhroobo decription" />
            <div className="singleproductpage">
                <div className='container'>
                    {/* <div className="dangerouslySetInnerHTML" dangerouslySetInnerHTML={{ __html: content?.content?.description }} /> */}
                    Product
                </div>
            </div>
        </FrontendLayouts>
    )
}





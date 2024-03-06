

import FrontendLayouts from '@/components/Layouts/Frontend/FrontendLayouts'
import Head from 'next/head'
import Image from 'next/image'
import Meta from "@/components/Meta";
import { useRouter } from 'next/router'
import { useEffect, useState, React } from 'react';
import axios from 'axios';
import { getSession, useSession } from 'next-auth/react';

export default function Page() {
    const router = useRouter();
    const slug = router.query.slug;
    const { data:userdata, status, update, session }= useSession();

    console.log('slug=', slug);

    const [content, setContent] = useState(null);
    const loadPage = async () => {

        try {
            const response = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-page-content/`+slug,{
                headers: {
                    Authorization: `Bearer ${userdata?.accessToken}`,
                },
            });
            setContent(response.data);
        } catch (error) {
            console.log('error ', error);
        }

    }


    useEffect(() => {
        if (slug) {
            loadPage();
        }
        
    }, [slug]); 

    return (
        <FrontendLayouts>
            <Meta title= {`${content?.content?.title} | Dhroobo`} keywords="dhroobo keywords" decription="dhroobo decription" />
            <div className="dynamic_pages">
                <div className='container'>
                    <div className='dangerouslySetInnerHTML' dangerouslySetInnerHTML={{ __html: content?.content?.description }} />
                </div>
            </div>
        </FrontendLayouts>
    )
}





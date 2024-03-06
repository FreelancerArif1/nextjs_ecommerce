import FrontendLayouts from '@/components/Layouts/Frontend/FrontendLayouts'
import Head from 'next/head'
import Image from 'next/image'
import Meta from "@/components/Meta";
import Link from 'next/link';
import { useEffect, useState } from 'react';
const Index = () => {
    const [loadCareer, setloadCareer] = useState([]);
    const career = async () => {
      try {
          const response = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-career`);
          const data = await response.json();
          setloadCareer(data.career);
      } catch (error) {
  
      }
    }
    useEffect(() => {
        career();
    }, []); 
    return (
        <FrontendLayouts>
            <Meta title="Career | Dhroobo" keywords="dhroobo keywords" decription="dhroobo decription" />

            <div className="Aboutpage">




        <div className="container">
            <div className="row">
                <div className="col-12 col-lg-12">
                    <div className="title"><h3><p><span className='careercls post_title text-uppercase'>Career</span></p></h3></div>
                </div>
            </div>

            {loadCareer?(
            <div className="row">
                {loadCareer?.map((data, index) => (
                <div key={index} className="col-12 col-lg-12">
                    <div className="career-item">
                        <div className="row career_row">
                    
                            <div className="col-md-10">
                                <p className="post_title text-uppercase"><b>{ data.position }</b></p>
                                <p className="date_item">Circular Date: { data.job_date }</p>
                                <p className="date_item">Application Deadline: { data.apply_date }</p>
                            </div>
                            <div className="col-md-2 text-center">
                            <Link href={`/career/${data.id}`}>  <button className="btn btn-animation btn-sm mx-auto mt-sm-3 mt-2 text-light">Apply Now <i className="fa-solid fa-arrow-right icon"></i></button></Link>
                            </div>
                        </div>
                    </div>
                </div>
                ))}
            </div>
            ):('')}

        </div>
    




            </div>
        </FrontendLayouts>
    )
}

export default Index

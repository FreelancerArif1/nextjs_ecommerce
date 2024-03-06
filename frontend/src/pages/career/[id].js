

import FrontendLayouts from '@/components/Layouts/Frontend/FrontendLayouts'
import Head from 'next/head'
import Image from 'next/image'
import Meta from "@/components/Meta";
import { useRouter } from 'next/router'
import { useEffect, useState, React } from 'react';
import axios from 'axios';
import { getSession, useSession } from 'next-auth/react';
import Input from '@/components/Input';
import { ToastContainer, toast } from 'react-toastify';


export default function Page() {
    const router = useRouter();
    const id = router.query.id;
    const { data:userdata, status, update, session }= useSession();
    const [careerSingle, setCareer] = useState(null);
    const loadCareer = async () => {
        try {
            const response = await axios.get(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/get-career-details/`+id,{
                headers: {
                    Authorization: `Bearer ${userdata?.accessToken}`,
                },
            });
            setCareer(response.data.career);
        } catch (error) {
            console.log('error ', error);
        }
    }
    useEffect(() => {
        if (id) {
            loadCareer();
        }
    }, [id]); 



    const [selectedResume, setResume] = useState(null);
    const uploadResume = (event) => {
        if (event.target.files && event.target.files[0]) {
          const i = event.target.files[0];
          //console.log('upload resume==', i);
          setResume(i);
          //setCreateObjectURL(URL.createObjectURL(i));
        }
    }


    const candidateChange = (e) => {
        const { name, value } = e.target;
        setcandidate((prevData) => ({ ...prevData, [name]: value }));
    }
    const [candidate, setcandidate] = useState({
        first_name: '',
        last_name: '',
        phone: '',
        email: '',
        cover_letter: '',
        job_id: id,
        resume: selectedResume,
    });




    const applyforjobform = async (e) => {
        e.preventDefault();
        const token = localStorage.getItem('token');
        let formData = new FormData();
        formData.append('first_name', candidate.first_name);
        formData.append('last_name', candidate.last_name);
        formData.append('phone', candidate.phone);
        formData.append('email', candidate.email);
        formData.append('cover_letter', candidate.cover_letter);
        formData.append('job_id', id);
        formData.append('resume', selectedResume);
        const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/apply/for/job`, formData, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        if (response.data.status == 50) {
            $('.errormessage').text('');
            var p = response.data.message;
            for (const [key, value] of Object.entries(p)) {
                $('.'+`${key}`).text(`${value}`);
              }
        }else if(response.data.status == 0){
            $('.errormessage').text('');
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
            $('.errormessage').text('');
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
            setcandidate({first_name: '',last_name: '',phone: '',email: '',job_id: id,resume: '', cover_letter:''});
        }
    }

    return (
        <FrontendLayouts>
            <Meta title= {`${ 'Career' } | Dhroobo`} keywords="dhroobo keywords" decription="dhroobo decription" />
    
            <div className="container">
                <div className="row">
                    <div className="col-12 col-md-6 col-sm-12 col-lg-7">
                        <div className="row">
                            <div className="col-12 col-lg-12">
                                <div className="title"><h3><p><span className='careercls post_title text-uppercase'>Career</span></p></h3></div>
                            </div>
                        </div>

                        {careerSingle ? (
                        <div className="title"><h3> <p><span className="sinlgeCareerStyle">Position: { careerSingle.position }</span></p></h3></div>
                        ):('')}
                        </div>

                    <div className="col-12 col-md-6 col-sm-12 col-lg-5">
                        <div  className="title"><h3><p><span className="sinlgeCareerStyle">Apply here</span></p></h3></div>
                    </div>
                </div>

                <div className="row">
                    <div className="col-12 col-md-6 col-sm-12 col-lg-7">
                        {careerSingle ? (
                        <div className="career-details">
                            <p><b>Circular Date: { careerSingle.job_date }</b></p>
                            <p><b>Application Deadline: { careerSingle.apply_date }</b></p>
                            <div  className="pb-5 dangerouslySetInnerHTML" dangerouslySetInnerHTML={{ __html: careerSingle?.description }} />
                        </div>
                        ):('')}
                    </div>

                    <div className="col-12 col-md-6 col-sm-12 col-lg-5">
                        <div className="card border_site rounded-0">
                            <form onSubmit={applyforjobform} encType='multipart/form-data' className='applyforjobform'>
                            <div className="card-body p-3">
                                <div className="form-group">
                                    <label> First Name <span className="red">*</span> </label>
                                    <div className="Input-group">
                                        <Input value={candidate.first_name} type="text" name="first_name" className="form-control" id="first_name" placeholder="First Name" onChange={candidateChange} />
                                        <small className='errormessage first_name'></small>
                                    </div>
                                </div>
                                <div className="form-group">
                                    <label> Last Name <span className="red">*</span> </label>
                                    <div className="Input-group">
                                        <Input value={candidate.last_name}  type="text" name="last_name" className="form-control" id="last_name" placeholder="Last Name" onChange={candidateChange} />
                                        <small className='errormessage last_name'></small>
                                    </div>
                                </div>
                                <div className="form-group">
                                    <label>Phone <span className="red">*</span></label>
                                    <div className="Input-group mb-2 mb-sm-0">
                                        <Input type="text" value={candidate.phone}  name="phone" className="form-control" id="phone" placeholder="Phone" onChange={candidateChange} />
                                        <small className='errormessage phone'></small>
                                    </div>
                                </div>
                                <div className="form-group">
                                    <label>Email</label>
                                    <div className="Input-group mb-2 mb-sm-0">
                                        <Input type="email" value={candidate.email}  name="email" className="form-control" id="email" placeholder="Email" onChange={candidateChange} />
                                        <small className='errormessage email'></small>
                                    </div>
                                </div>
                                <div className="form-group">
                                    <label> Resume <span className="red">*</span></label>
                                    <div className="Input-group mb-2 mb-sm-0">
                                        <Input type="file" name="resume"  className="form-control" onChange={uploadResume} />
                                        <small className='errormessage resume'></small>
                                    </div>
                                </div>
                                <div className="form-group">
                                    <label>Cover Letter <span className="red">*</span></label>
                                    <div className="Input-group mb-2 mb-sm-0">
                                        <textarea value={candidate.cover_letter}  type="text" className="form-control" name="cover_letter" id="cover_letter" placeholder="Write cover letter" rows="3" cols="" onChange={candidateChange}></textarea>
                                    
                                        <small className='errormessage cover_letter'></small>
                                    </div>
                                </div>
                                <div className="text-center">
                                    <Input type="submit" name="submit" value="Submit" className="btn btn-primary btn-block rounded-0 py-2 w-100 mt-3" />
                                </div>
                            </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>



        </FrontendLayouts>
    )
}





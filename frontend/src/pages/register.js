import FrontendLayouts from '@/components/Layouts/Frontend/FrontendLayouts'
import Head from 'next/head'
import Image from 'next/image'
import Meta from "@/components/Meta";
import axios from 'axios';
import Input from '@/components/Input'
import InputError from '@/components/InputError'
import { useState } from 'react'



const Register = () => {
    


    const [formData, setFormData] = useState({
        name: '',
        phone: '',
        email: '',
        password: '',
    });
    
    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData((prevData) => ({ ...prevData, [name]: value }));
    }
    
    const handleSubmit = async (e) => {
        e.preventDefault();
        const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/user-register`, formData);
        if (response.data.status == 0) {
            response.data.message.name ? $('.name_error').text(response.data.message.name) : $('.name_error').text('');
            response.data.message.email ? $('.email_error').text(response.data.message.email) : $('.email_error').text('');
            response.data.message.phone ? $('.phone_error').text(response.data.message.phone) : $('.phone_error').text('');
            response.data.message.password ? $('.password_error').text(response.data.message.password) : $('.password_error').text('');
        }else{
            $('.errormessage').text('');
        }
    }

    return (
        <FrontendLayouts>
        <Meta title="Register page" keywords="dhroobo keywords" decription="dhroobo decription" />
        <div className="Registerpage">
                
            <section className="log-in-section section-b-space">
                <div className="container-fluid-lg w-100">
                    <div className="row">
                        <div className="col-xxl-6 col-xl-5 col-lg-6 d-lg-block d-none ms-auto">
                            <div className="image-contain">
                                <Image src="/assets/images/inner-page/sign-up.png"  className="img-fluid"  width={550} height={540} alt="image" />
                            </div>
                        </div>

                        <div className="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
                            <div className="log-in-box">
                                <div className="log-in-title">
                                    <h4>Create New Account </h4>
                                </div>

                                <div className="Input-box">
                                    <form className="row g-4"  onSubmit={handleSubmit}>
                                        <div className="col-12">
                                            <div className="form-floating theme-form-floating">
                                                <Input name="name" value={formData.name} onChange={handleChange}  type="text" className="form-control" id="fullname" placeholder="Full Name" />
                                                <label htmlFor="fullname">Full Name</label>
                                            </div>
                                            <small className='errormessage name_error'></small>

                                        </div>
                                        <div className="col-12">
                                            <div className="form-floating theme-form-floating">
                                                <Input name="phone" value={formData.phone} onChange={handleChange}  type="text" className="form-control" id="phone" placeholder="Phone" />
                                                <label htmlFor="phone">Phone</label>
                                            </div>
                                            <small className='errormessage phone_error'></small>

                                        </div>
                                        <div className="col-12">
                                            <div className="form-floating theme-form-floating">
                                                <Input  name="email" value={formData.email} onChange={handleChange}  type="email" className="form-control" id="email" placeholder="Email Address" />
                                                <label htmlFor="email">Email Address</label>
                                            </div>
                                            <small className='errormessage email_error'></small>
                                        </div>

                                        <div className="col-12">
                                            <div className="form-floating theme-form-floating">
                                                <Input  name="password" value={formData.password} onChange={handleChange}  type="password" className="form-control" id="password"
                                                    placeholder="Password" />
                                                <label htmlFor="password">Password</label>
                                            </div>
                                            <small className='errormessage password_error'></small>

                                        </div>

                            

                                        <div className="col-12">
                                            <button className="btn btn-animation w-100" type="submit">Sign Up</button>
                                        </div>
                                    </form>
                                </div>


                                <div className="sign-up-box">
                                    <h4>Already have an account?</h4>
                                    <a href="login.html">Log In</a>
                                </div>
                            </div>
                        </div>

                        <div className="col-xxl-7 col-xl-6 col-lg-6"></div>
                    </div>
                </div>
            </section>

        </div>
        </FrontendLayouts>
    )
}

export default Register

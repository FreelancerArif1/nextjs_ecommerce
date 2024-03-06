import FrontendLayouts from '@/components/Layouts/Frontend/FrontendLayouts'
import Head from 'next/head'
import Image from 'next/image'
import Meta from "@/components/Meta";

const About = () => {
    function handleClick() {
        alert('kkkkkk');
    }

    return (
        <FrontendLayouts>
            <Meta title="About-us | Dhroobo" keywords="dhroobo keywords" decription="dhroobo decription" />

            <div className="Aboutpage">

            Aboutpage
            </div>
        </FrontendLayouts>
    )
}

export default About

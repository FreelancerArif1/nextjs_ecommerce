// path/to/withAuth.js
import { useRouter } from 'next/router';
import { useEffect, useState } from 'react';
import { getSession, signIn, signOut, useSession } from 'next-auth/react';


const withAuth = (WrappedComponent) => {

    const Wrapper = (props) => {
        const router = useRouter();
        const { data: session, status } = useSession();
        if(status == 'loading'){
            console.log('Login checking from withauth.');
        }else if(status == 'authenticated'){
            console.log('User logged confirm from withauth.');
            localStorage.setItem('token', session.accessToken);
        }else if(status == 'unauthenticated'){
            if(typeof window !== 'undefined') {
                router.push('/login');
            }
        }

        return <WrappedComponent session={session} {...props} />;
    };

    return Wrapper;
};

export default withAuth;

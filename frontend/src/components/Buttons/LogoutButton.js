// components/LogoutButton.js
"use client"
import { signOut, useSession } from 'next-auth/react';

const LogoutButton = () => {
  const { data: session } = useSession();
  const handleLogout = async () => {
    localStorage.setItem('token', null);
    await signOut({redirect: false, callbackUrl: "login"});
  }
  if (!session) {
    return null; // Render nothing if the user is not authenticated
  }
  return (
    <li className="product-box-contain" onClick={handleLogout} >
      Logout
    </li>
  );
};

export default LogoutButton;

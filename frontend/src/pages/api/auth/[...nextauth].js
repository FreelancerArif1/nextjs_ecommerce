import NextAuth from "next-auth"
import GithubProvider from "next-auth/providers/github"
import CredentialsProvider from "next-auth/providers/credentials";
import axios from 'axios';

export const authOptions = {
  providers: [
    // GithubProvider({
    //   clientId: process.env.GITHUB_ID,
    //   clientSecret: process.env.GITHUB_SECRET,
    // }),
    CredentialsProvider({
      name: "Credentials",
      credentials: {
        phone: { label: "phone", type: "text", placeholder: "phone" },
        password: { label: "Password", type: "password" }
      },
      async authorize(credentials, req) {
        const loginData = {
          phone:  credentials.phone,
          password:  credentials.password,
      };

        const response = await axios.post(`${process.env.NEXT_PUBLIC_BACKEND_URL}/api/v1/login`, loginData);


        if(response.data.status == 1){
          const user = { 
            id: response.data.customer.id, 
            name:  response.data.customer.name, 
            email: response.data.customer.email, 
            token: response.data.token, 
            customer: response.data.customer,
            all_orders: response.data.all_orders,
            carts: response.data.carts,
            compares: response.data.compares,
            wishlists: response.data.wishlists,
          }
          return user
        }else{
          return null
        }
      }
    })

  ],
  callbacks: {
    async jwt({ token, user, trigger,session }) {
      if (user) {
        token.accessToken = user.token,
        token.customer = user.customer,
        token.customer_order =  user.customer_order,
        token.all_orders =  user.all_orders,
        token.carts =  user.carts,
        token.compares =  user.compares,
        token.wishlists =  user.wishlists
        
      }
      if (trigger === "update" && session?.customer_data) {
        token.customer = session.customer_data
      }
      if (trigger === "update" && session?.wishlist_data) {
        token.wishlists = session.wishlist_data
      }
      if (trigger === "update" && session?.compares_data) {
        token.compares = session.compares_data
      }

      
      return token
    },


    async session({ session, token, user }) {
      session.accessToken = token.accessToken,
      session.customer = token.customer,
      session.customer_order = token.customer_order,
      session.all_orders = token.all_orders,
      session.carts = token.carts,
      session.compares = token.compares,
      session.wishlists = token.wishlists
      session.testname = token.testname
      return session
    },


  }
}

export default NextAuth(authOptions)
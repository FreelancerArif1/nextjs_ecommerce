


  module.exports = {
    images: {
      //domains: ['127.0.0.1'],
      domains: ['https://dhroobo-frontend.dhakaitsolutions.com'],
    },
    useFileSystemPublicRoutes: false,
    reactStrictMode: true,
    // async rewrites() {
    //   return [
    //     {
    //       source: '/api/auth/:path*',
    //       destination: 'http://localhost:3000/api/auth/[...nextauth]',
    //     },
    //   ];
    // },


    // async rewrites() {
    //   return [
    //     {
    //       source: '/src/pages/api/auth/[...nextauth]',
    //       destination: '/src/pages/api/auth/[...nextauth]', // Adjust the path accordingly
    //     },
    //   ];
    // },

    async rewrites() {
      return [
        {
          source: '/src/pages/api/auth/[...nextauth]',
          //destination: 'http://localhost:3000/api/auth/[...nextauth]', // Adjust the path accordingly
          destination: 'https://dhroobo-frontend.dhakaitsolutions.com/api/auth/[...nextauth]', // Adjust the path accordingly
        },
      ];
    },



    env: {
      GITHUB_ID: '3ecc28d5389cca833eeb',
      GITHUB_SECRET: 'f7bd65b98b8c9345f84deabcc95ce7b124c0ec80',
      NEXTAUTH_SECRET: 'mQ46qpFwfE1BHuqMC+qlm19qBAD9fVPgh28werwe3ASFlAfnKjM=',
      //APP_FRONTEND:'http://127.0.0.1:3000',
      APP_FRONTEND:'https://dhroobo-frontend.dhakaitsolutions.com',
      
    },


  };
module.exports = {
  css: {
    loaderOptions: {
      sass: {
        additionalData: '@import "@/styles/base.scss";',
      },
    },
  },
  devServer: {
    port: 8100,
    historyApiFallback: true,
  },
};

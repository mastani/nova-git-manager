Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'nova-git-manager',
      path: '/nova-git-manager',
      component: require('./components/Tool'),
    },
  ])
})

export const getCookieValue = (searchKey: string) => {
  if (!searchKey) {
    return '';
  }
  let val = '';
  document.cookie.split(';').forEach(cookie => {
    const [key, value] = cookie.split('=')
    if (key === searchKey) {
      return val = value;
    }
  })
  return val;
}

export const getContentHeight = () => {
  const appHeight = document.getElementById('app')?.clientHeight
  const headerHeight = document.getElementById('header')?.clientHeight
  const footerHeight = document.getElementById('footer')?.clientHeight
  return (appHeight ? appHeight : 0)
    - (headerHeight ? headerHeight : 0)
    - (footerHeight ? footerHeight : 0)
}

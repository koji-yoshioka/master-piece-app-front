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

export const getFullHeight = () => {
  const headerHeight = document.getElementById('header')?.clientHeight
  const mainHeight = document.getElementById('main')?.clientHeight
  const footerHeight = document.getElementById('footer')?.clientHeight

  // bodyの高さ
  const bodyHeight = document.body.offsetHeight

  // header+main+footerの高さ
  const contentHeight = (headerHeight ? headerHeight : 0)
    // メインタグの高さはヘッダの高さも含まれているため、そのぶんを引く
    + (mainHeight ? mainHeight : 0) - (headerHeight ? headerHeight : 0)
    + (footerHeight ? footerHeight : 0)

  // 長い方を返す
  return bodyHeight > contentHeight
    ? bodyHeight
    : contentHeight
}

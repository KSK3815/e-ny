if (document.getElementById('header-search-submit')) {
  const header_input_submit = document.getElementById('header-search-submit')
  header_input_submit.addEventListener('click', () => {
    this.event.preventDefault()
    this.event.stopPropagation()
    let input_value = document.getElementById('header-search-input').value
    input_value = input_value.trim()
    if (!input_value) {
      window.alert('フォームを入力してください')
      return
    }
    const input = document.getElementById('header-search')
    input.submit()
  })
}

if (document.getElementById('index-search-submit')) {
  const index_input_submit = document.getElementById('index-search-submit')
  index_input_submit.addEventListener('click', () => {
    this.event.preventDefault()
    this.event.stopPropagation()
    let input_value = document.getElementById('index-search-input').value
    input_value = input_value.trim()
    if (!input_value) {
      window.alert('フォームを入力してください')
      return
    }
    const input = document.getElementById('index-search')
    input.submit()
  })
}

if (document.getElementById('js-header')) {
  const interval = 80
  let lastTime = new Date().getTime() - interval
  let lastScrollPt = window.scrollY

  const header = document.getElementById('js-header')

  window.addEventListener(
    'scroll',
    () => {
      if (lastTime + interval < new Date().getTime()) {
        lastTime = new Date().getTime()

        if (lastScrollPt > window.scrollY) {
          lastScrollPt = window.scrollY
          header.classList.remove('scrolled')
        } else {
          lastScrollPt = window.scrollY
          header.classList.add('scrolled')
        }
      }
    },
    { passive: true }
  )
}

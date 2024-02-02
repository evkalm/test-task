
const form = document.getElementById('postForm')
form.addEventListener('submit', handleSubmit)


function handleSubmit(event) {
	event.preventDefault()
	
	const inputValue = document.getElementById('inputValue').value
	sendRequest(inputValue)
}


function sendRequest(value) {

	const params = new URLSearchParams()
	params.append('key', value)

	fetch(REQUEST_URL, {
		method: 'POST',
		body: params
	})
	.then(response => {
		console.log(response)
	})
	.catch(error => {
		console.error('Ошибка выполнения POST-запроса:', error)
	})
}


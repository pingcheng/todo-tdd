import axios from 'axios';

function ajax(options) {
	return new Promise((resolve, reject) => {

		const client = axios.create({
			headers: {
				'Content-Type': 'application/json',
			},
			withCredentials: true,
			timeout: 30000,
		});

		client(options)
		.then(response => {
			resolve(response.data);
		})
		.catch(error => {
			reject(error.response.data);
		})
	});
}

function request(method, url, options) {
	options.url = url;
	options.method = method;
	return ajax(options);
}

function initOptions(options) {

	if (options === undefined) {
		return {};
	}

	if (typeof options !== 'object') {
		return {};
	}

	return options;
}

const ApiClient = {

	get(url, params, options) {
		options = initOptions(options);
		options.params = params;
		return request('get', url, options);
	},

	post(url, data, options) {
		options = initOptions(options);
		options.data = data;
		return request('post', url, options);
	},

	put(url, data, options) {
		options = initOptions(options);
		options.data = data;
		return request('put', url, options);
	},

	delete(url, data, options) {
		options = initOptions(options);
		options.data = data;
		return request('delete', url, options);
	},

	patch(url, data, options) {
		options = initOptions(options);
		options.data = data;
		return request('patch', url, options);
	}

};

export default ApiClient;
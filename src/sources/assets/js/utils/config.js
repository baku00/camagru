class Config {
	static #instance = null;
	#apiUrl = '';

	static getInstance() {
		if (!Config.instance) {
			Config.instance = new Config();
		}

		return Config.instance;
	}

	getApiUrl() {
		return this.apiUrl;
	}

	setApiUrl(apiUrl) {
		this.apiUrl = apiUrl;
	}
}

var schema=	{
		"type":"object",
		"required":true,
		"properties":{
			"info": {
				"type":"object",
				"required":true,
				"properties":{
					"choices": {
						"type":"integer",
						"required":true
					},
					"multiple": {
						"type":"boolean",
						"required":true
					},
					"negative": {
						"type":"number",
						"required":true
					},
					"time": {
						"type":"object",
						"required":true,
						"properties":{
							"hour": {
								"type":"integer",
								"required":true
							},
							"min": {
								"type":"integer",
								"required":true
							},
							"sec": {
								"type":"integer",
								"required":true
							}
						}
					}
				}
			},
			"mcq": {
				"type":"array",
				"required":true,
				"items": {
					"type":"object",
					"required":false,
					"properties":{
						"question": {
							"type":"string",
							"required":true
						},
						"a": {
							"type":"string",
							"required":true
						},
						"b": {
							"type":"string",
							"required":true
						},
						"c": {
							"type":"string",
							"required":true
						},
						"d": {
							"type":"string",
							"required":true
						},
						"e": {
							"type":"string",
							"required":false
						},
						"correct": {
							"type":"string",
							"required":true
						}
					}
				}
			}
		}
	};

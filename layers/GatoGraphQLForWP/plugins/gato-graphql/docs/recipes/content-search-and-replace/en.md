# Content search and replace


Talk about $1 in docs:
	_strRegexReplaceMultiple(searchRegex: ["/^https?:\\/\\//", "/([a-z]*)/"], replaceWith: ["", "$1$1"], in: "https://gatographql.com")
	regexWithHashMultiple: _strRegexReplaceMultiple(searchRegex: ["#^https?://#", "/([a-z]*)/"], replaceWith: ["", "$1$1"], in: "https://gatographql.com")
	regexWithVarsMultiple: _strRegexReplaceMultiple(searchRegex: ["/<!\\[CDATA\\[([a-zA-Z !?]*)\\]\\]>/", "/([a-z]*)/"], replaceWith: ["<Inside: $1>", "$1$1"], in: "<![CDATA[Hello world!]]><![CDATA[Everything OK?]]>")
	regexWithVarsAndLimitMultiple: _strRegexReplaceMultiple(searchRegex: ["/<!\\[CDATA\\[([a-zA-Z !?]*)\\]\\]>/", "/([a-z]*)/"], replaceWith: ["<Inside: $1>", "$1$1"], in: "<![CDATA[Hello world!]]><![CDATA[Everything OK?]]>", limit: 1)



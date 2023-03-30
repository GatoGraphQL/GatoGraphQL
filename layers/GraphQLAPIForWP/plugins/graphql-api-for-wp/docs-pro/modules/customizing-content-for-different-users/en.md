# Customizing content for different users

one way:
    content @if (value: $isAdmin)
        @remove
    content: excerpt @unless (value: $isAdmin)
        @remove
2nd way:
    _if(...)

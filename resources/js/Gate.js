export default class Gate {
    constructor(user) {
        this.user = user
    }

    isAuthor(){
        return this.user.type === 'author'
    }

    isAdmin(){
        return this.user.type === 'admin'
    }

    isUser(){
        return this.user.type === 'user'
    }

    isAdminOrAuthor() {
        return this.user.type === 'author' || this.user.type === 'admin'
    }

    isAuthorOrUser() {
        return this.user.type === 'author' || this.user.type === 'user'
    }
}

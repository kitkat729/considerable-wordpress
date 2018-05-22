console.log('recent-articles global.js loaded')

const defaultRT = moment().localeData()._relativeTime
const smallRT = {
        future : 'in %s',
        past   : '%s ago',
        s  : 'a few seconds',
        ss : '%d s',
        m  : '1 m',
        mm : '%d m',
        h  : '1 h',
        hh : '%d h',
        d  : '1 d',
        dd : '%d d',
        M  : '1 m',
        MM : '%d m',
        y  : '1 y',
        yy : '%d y'
    }

Vue.component('blog-post', {
	props: ['title', 'permalink', 'thumbnail', 'timestamp', 'category', 'author'],
	template:
	`<div class="flex-container post">
		<div class="column column-left" v-html="thumbnail"></div>
		<div class="column column-right">
			<div class="category-block"><span class="category-tag" v-html="category.name"></span><span class="timestamp" v-if="timestamp.small"> | {{ timestamp.small }}</span></div>
			<a :href="permalink" :title="title"><h3 v-html="title"></h3></a>
			<div class="byline-block"><span class="author" v-if="author.link">By <span v-html="author.link"></span></span><span class="timestamp" v-if="timestamp.large"> {{ timestamp.large }}</span></div>
		</div>
	</div>`
})

new Vue({
	el: '#recent-posts',
	data: {
		posts: []
	},
	created: function() {
		const instance = this
		const url = document.getElementById(this.$options.el.substr(1)).getAttribute('apiurl')

		fetch(url)
			.then( res => {
				return res.json()
			})
			.then( data => {
				instance.posts = data.posts

				instance.posts = instance.posts.map(function(post, i) {
					post.timestamp = {
						original: post.timestamp,
						large: moment(post.timestamp).fromNow()
					}
					return post
				})

				moment.updateLocale(moment().locale(), { relativeTime: smallRT })
				instance.posts = instance.posts.map(function(post, i) {
					post.timestamp.small = moment(post.timestamp.original).fromNow()

					return post
				})
				moment.updateLocale(moment().locale(), { relativeTime: defaultRT })
			})
	}
})

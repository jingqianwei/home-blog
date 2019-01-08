<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">
                        {{ reversedMessage() }}
                    </div>
                    <div class="card-body">
                        {{ message }} {{ name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        // 初始化数据
        data() {
            return {
                message: 'Hello Laravel!',
                name: ''
            }
        },
        // vue实例化后执行
        created() {
            this.name = '哈哈哈'
        },
        // DOW加载完成后执行
        mounted() {
            //this.name = '嘻嘻嘻';
            console.log('Component mounted.')
        },
        // 在组件中，使用方法， 在vue实例中使用computed()计算属性不用加括号
        methods: {
            reversedMessage: function () {
                // `this` 指向vue实例
                return this.message.split('').reverse().join('')
            }
        }
    }
</script>

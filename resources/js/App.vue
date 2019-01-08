<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">
                        {{ reversedMessage() }}
                    </div>
                    <div class="card-body"
                         v-bind:class="classObject">
                        {{ message }} {{ name }}
                    </div>
                    <div v-if="flag" class="card-body">
                        条件渲染
                    </div>
                    <div v-else class="card-body" @click="add">
                        not
                    </div>
                    <hr/>
                    <!-- 父组件向子组件传值(值为变量) -->
                    <example-component v-bind:title="message"></example-component>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ExampleComponent from './components/ExampleComponent'
    export default {
        // 组件注册
        components: {
            ExampleComponent
        },
        // 初始化数据
        data() {
            return {
                message: 'Hello Laravel!',
                name: '',
                classObject: 'card',
                flag: false // 来定义内容是否展示
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
            },
            add: function () {
                this.message = '点击事件'
            }
        }
    }
</script>

<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">{{ title }}</div>

                    <div class="card-body">
                        I'm an example component.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            // 用于merge和rebase测试用的
        },
        props: {
            // 限定父组件传过来的参数类型
            title: String
        },
        mounted() {
            // 两种写法都可以
            //console.log(this.$attrs.title)
            console.log(this.title)
        }
    }
</script>

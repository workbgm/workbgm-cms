/**
 * Created by king on 2017/5/26.
 */
require.config({
    baseUrl: '',
    map: {
        '*': {
            'require-css': 'lib/css.min'
        }
    },
    paths:{
        'test':'lib/test',
        'jquery':'lib/jquery.min',
        'bootstrap':'lib/bootstrap'
    },
    shim:{
        'test':{
            // exports:'test'
            init:function(){
                return {
                    test:test,
                    suc:suc,
                }
            }
        },
        'bootstrap':{
            'deps':['jquery','require-css!css/bootstrap.css']
        }
    }
});
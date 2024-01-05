from celery import Celery

app = Celery(
    'tasks',
    broker='pyamqp://one-scan:one-scan@127.0.0.1//',
    backend='redis://127.0.0.1:6379/0',
)

# 这个prefix key的格式是.env内的APP_NAME_database，若APP_NAME包含-，则改成_
app.conf.result_backend_transport_options = {'global_keyprefix': 'one_scan_database'}


@app.task
def add(arg1, arg2):
    print(arg1 + arg2)
    return arg1 + arg2

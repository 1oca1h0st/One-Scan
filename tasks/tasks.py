from celery import Celery

app = Celery('tasks', broker='pyamqp://one-scan:one-scan@127.0.0.1//')


@app.task
def add(arg1, arg2):
    print(arg1+arg2)
    return arg1 + arg2

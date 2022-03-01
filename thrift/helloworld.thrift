namespace php Services.HelloWorld
service HelloWorld
{
    string sayHello(1:string name,2:string key);

    string sayWorld(1:string name);
}
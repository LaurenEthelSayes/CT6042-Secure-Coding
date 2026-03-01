import java.io.*;

class Customer implements Serializable{
    public String name;
    public String address;

    private void readObject (ObjectInputStream in) {
        try {
            in.defaultReadObject();
            System.out.println("readObject from Customer class");
            System.out.println("Customer name: " + name);
            System.out.println("Customer address: " + address);
        } catch (Exception e) {
            System.out.println("exception : " + e.toString());
        }
    }
}
package biz.prolike.hereiam.containers;

import java.io.Serializable;

public class ChildData implements Serializable {
    private String photo;
    private String id_user;
    private String name;
    private String email;

    public ChildData(){}

    public ChildData(String id_user, String name, String email, String photo)
    {
        this.name = name;
        this.photo = photo;
        this.email = email;
        this.id_user = id_user;
    }

    public void setName(String name){ this.name = name;}
    public String getName(){return this.name;}

    public void setEmail(String email){ this.email = email;}
    public String getEmail(){return this.email;}

    public void setPhoto(String photo){ this.photo = photo;}
    public String getPhoto(){return this.photo;}

    public void setId(String id_user){ this.id_user = id_user;}
    public String getId(){return this.id_user;}
}

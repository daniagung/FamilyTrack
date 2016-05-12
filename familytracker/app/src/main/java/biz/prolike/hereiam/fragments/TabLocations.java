package biz.prolike.hereiam.fragments;


import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v7.widget.CardView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.JsonObjectRequest;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

import biz.prolike.hereiam.AppController;
import biz.prolike.hereiam.R;
import biz.prolike.hereiam.adapters.PlaceAdapter;
import biz.prolike.hereiam.containers.LocData;
import biz.prolike.hereiam.utils.Config;
import biz.prolike.hereiam.utils.DatabaseHandler;

/**
 * Created by Edwin on 15/02/2015.
 */
public class TabLocations extends Fragment {

    private Context context=null;
    private DatabaseHandler db;
    private CardView card1;
    private CardView card2;
    private EditText email;
    private Button   cauta;
    SharedPreferences prefs = null;
    String tag_json_obj = "json_obj_req";


    private ListView listview=null;
    private List<LocData> list=new ArrayList<LocData>();
    private PlaceAdapter adapter;
    private boolean load = false;
    private Locale myLocale;
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View v =inflater.inflate(R.layout.tab_1,container,false);
        context=getActivity();
        loadLocale();
        card2 = (CardView) v.findViewById(R.id.card_view2);
        email = (EditText) v.findViewById(R.id.ChildEmail);
        cauta = (Button) v.findViewById(R.id.AddChild);
        listview=(ListView) v.findViewById(R.id.listview);

        prefs = context.getSharedPreferences("biz.prolike.hereiam", context.MODE_PRIVATE);

        cauta.setOnClickListener( new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                // TODO Auto-generated method stub
                searchMail();
            }
        });
        db = new DatabaseHandler(context);
        Integer childs = db.getChildCount();
        if(childs > 0)
        {
            listview.setVisibility(v.VISIBLE);
            card2.setVisibility(v.GONE);
            if(!load) {
                getLocationDetails();
            }
            adapter = new PlaceAdapter(getActivity(), list);
            listview.setAdapter(adapter);


            listview.setOnItemClickListener(new AdapterView.OnItemClickListener() {

                @Override
                public void onItemClick(AdapterView<?> parent, View view,
                                        int position, long id) {
                    LocData loc = list.get(position);
                    String lat =loc.getLat();
                    String lon = loc.getLon();
                    double latitude = Double.valueOf(lat);
                    double longitude = Double.valueOf(lon);
                    String label = "---";
                    String uriBegin = "geo:" + latitude + "," + longitude;
                    String query = latitude + "," + longitude + "(" + label + ")";
                    String encodedQuery = Uri.encode(query);
                    String uriString = uriBegin + "?q=" + encodedQuery + "&z=16";
                    Uri uri = Uri.parse(uriString);
                    Intent inten = new Intent(android.content.Intent.ACTION_VIEW, uri);
                    startActivity(Intent.createChooser(inten, "dialogTitle"));

                }

            });
        } else {
            listview.setVisibility(v.GONE);
            card2.setVisibility(v.VISIBLE);
        }

        return v;
    }

    public void changeLang(String lang)
    {
        if (lang.equalsIgnoreCase(""))
            return;
        myLocale = new Locale(lang);
        saveLocale(lang);
        Locale.setDefault(myLocale);
        android.content.res.Configuration config = new android.content.res.Configuration();
        config.locale = myLocale;
        getActivity().getBaseContext().getResources().updateConfiguration(config,  getActivity().getBaseContext().getResources().getDisplayMetrics());
        //updateTexts();
    }


    public void saveLocale(String lang)
    {
        String langPref = "Language";
        SharedPreferences prefs = context.getSharedPreferences("CommonPrefs", Activity.MODE_PRIVATE);
        SharedPreferences.Editor editor = prefs.edit();
        editor.putString(langPref, lang);
        editor.commit();
    }


    public void loadLocale()
    {
        String langPref = "Language";
        SharedPreferences prefs = context.getSharedPreferences("CommonPrefs", Activity.MODE_PRIVATE);
        String language = prefs.getString(langPref, "");
        changeLang(language);
    }

    public void getLocationDetails()
    {
        list.clear();
        load = true;
        String id = prefs.getString("seek","");
        String url = Config.URL_SITE+"/location/search?id="+id;
        Log.d("url",url);
        JsonArrayRequest movieReq = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        if(response.length()<= 0){
                            Toast.makeText(context, "The phone is disconnected or location of the phone is disconnected from settings",
                                    Toast.LENGTH_LONG).show();
                        }
                        // Parsing json
                        for (int i = 0; i < response.length(); i++) {
                            try {

                                JSONObject obj = response.getJSONObject(i);
                                LocData call = new LocData();
                                call.setLat(obj.getString("lat"));
                                call.setLon(obj.getString("lon"));
                                call.setDate(obj.getString("data"));
                                call.setAddress(obj.getString("address"));

                                // adding movie to movies array
                                list.add(call);

                            } catch (JSONException e) {
                                e.printStackTrace();
                            }

                        }

                        // notifying list adapter about data changes
                        // so that it renders the list view with updated data
                        adapter.notifyDataSetChanged();
                        load = false;
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                VolleyLog.d("", "Error: " + error.getMessage());

            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
    }



    public void searchMail()
    {
        String childMail = email.getText().toString();
        if(childMail.length() > 0) {
            String uri = Uri.parse(Config.URL_SITE+"/user/search")
                    .buildUpon()
                    .appendQueryParameter("email", childMail)
                    .build().toString();

            JsonObjectRequest jsonObjReq = new JsonObjectRequest(Request.Method.GET,
                    uri,
                    new Response.Listener<JSONObject>() {

                        @Override
                        public void onResponse(JSONObject response) {
                            Log.d("", response.toString());
                            //String str = response.toString();
                            try {
                                JSONObject user = response.getJSONObject("info");
                                String mail = user.getString("email");
                                String name = user.getString("name");
                                String photo = user.getString("photo");
                                Integer id_user = Integer.parseInt(user.getString("id_user"));

                                prefs.edit().putString("seek",user.getString("id_user")).commit();
                                db.addChild(id_user,name,mail,photo);
                                Intent i = context.getPackageManager()
                                        .getLaunchIntentForPackage(context.getPackageName());
                                i.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                                startActivity(i);

                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }
                    }, new Response.ErrorListener() {

                @Override
                public void onErrorResponse(VolleyError error) {
                    VolleyLog.d("", "Error: " + error.getMessage());
                    // hide the progress dialog
                }
            });
            AppController.getInstance().addToRequestQueue(jsonObjReq, tag_json_obj);
        }
    }
}

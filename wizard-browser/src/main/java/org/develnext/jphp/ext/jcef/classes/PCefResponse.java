package org.develnext.jphp.ext.jcef.classes;

import org.cef.handler.CefLoadHandler;
import org.cef.network.CefResponse;
import org.develnext.jphp.ext.jcef.JCefExtension;
import php.runtime.annotation.Reflection.*;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;

import java.util.LinkedHashMap;
import java.util.Map;

@Name("CefResponse")
@Namespace(JCefExtension.NS)
public class PCefResponse extends BaseWrapper<CefResponse> {
    interface WrappedInterface {
        @Property boolean readOnly();
        @Property int status();
        @Property String statusText();
        @Property String mimeType();
        @Property CefLoadHandler.ErrorCode error();

    }

    public PCefResponse(Environment env, CefResponse wrappedObject) {
        super(env, wrappedObject);
    }

    public PCefResponse(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public void __construct() {
        __wrappedObject = CefResponse.create();
    }

    @Getter
    protected Map<String, String> getHeaderMap() {
        LinkedHashMap<String, String> headerMap = new LinkedHashMap<>();
        getWrappedObject().getHeaderMap(headerMap);
        return headerMap;
    }

    @Setter
    protected void setHeaderMap(Map<String, String> headerMap) {
        getWrappedObject().setHeaderMap(headerMap);
    }
}
